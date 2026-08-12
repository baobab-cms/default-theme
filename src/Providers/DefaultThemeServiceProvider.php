<?php

declare(strict_types=1);

namespace Baobab\DefaultTheme\Providers;

use Baobab\Branding\Models\BrandingSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;

/**
 * Provider du thème par défaut (spec 19 §3.8) — trois responsabilités, toutes
 * en Laravel natif : c'est précisément ce qu'un intégrateur doit apprendre en
 * lisant ce thème (§9.2).
 *
 * **Ce qu'il ne fait pas, volontairement.** Il n'enregistre pas les vues du
 * thème : l'espace `theme::` est monté par requête par `ThemeViewRegistrar`
 * (spec 03 §5-6), et le faire ici casserait la cascade enfant → parent et la
 * prévisualisation. Le `composer.json` du package ne déclare pas non plus ce
 * provider dans `extra.laravel.providers` — un thème ne doit être chargé que
 * lorsqu'il est **actif**, ce que gère le cycle de vie des modules ; la
 * découverte de packages de Laravel le chargerait en permanence, thème inactif
 * compris.
 */
final class DefaultThemeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Réglages d'affichage lus par le thème lui-même (§3.8) : le Core n'en
        // lit rien. Voir la mise en garde en tête de config/theme.php sur la
        // fusion superficielle de mergeConfigFrom.
        $this->mergeConfigFrom(self::basePath().'/config/theme.php', 'theme');
    }

    public function boot(): void
    {
        // Aucune chaîne en dur dans les templates (§3.9) : `theme::theme.*`.
        $this->loadTranslationsFrom(self::basePath().'/resources/lang', 'theme');

        $this->composeSiteIdentity();
    }

    /**
     * L'en-tête porte l'identité du **site** (§4.2) : le logo de branding si
     * l'administrateur en a réglé un, sinon le nom du site.
     *
     * Passer par un view composer plutôt que d'interroger le modèle depuis la
     * vue n'est pas un détail de style — une requête Eloquent dans un template
     * de thème est un interdit du projet, et un thème de référence qui le
     * transgresserait enseignerait exactement ce qu'il ne faut pas faire.
     */
    private function composeSiteIdentity(): void
    {
        // `home` en a besoin autant que l'en-tête : son héros porte le nom du
        // site, seul contenu dont le mode par défaut dispose (§5.3).
        ViewFacade::composer(['theme::partials.header', 'theme::templates.home'], function (View $view): void {
            $branding = BrandingSetting::current();

            $view->with([
                'siteName' => (string) config('app.name', 'Baobab'),
                'siteLogo' => $branding->logo_media_id !== null ? $branding->logo : null,
            ]);
        });
    }

    /**
     * Racine du package, déduite de l'emplacement de ce fichier
     * (`{racine}/src/Providers/DefaultThemeServiceProvider.php`) — le thème
     * vit sous `vendor/` via un path repository (§2.2), jamais à un chemin
     * qu'on pourrait coder en dur.
     */
    private static function basePath(): string
    {
        return dirname(__DIR__, 2);
    }
}
