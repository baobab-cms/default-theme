<?php

declare(strict_types=1);

use Baobab\Branding\Actions\ApplyBrandProfile;
use Baobab\Branding\Actions\CompileDesignTokens;
use Baobab\Branding\Support\ResolveDesignTokens;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;

uses(RefreshDatabase::class);

/**
 * Test du preset (spec 19 §9.4), **sans navigateur**.
 *
 * La promesse du produit est que changer le profil de marque en admin
 * re-teinte le thème sans le moindre rebuild (§3.6). Elle ne se vérifie pas en
 * relisant du CSS : elle se vérifie en compilant les tokens sous deux profils
 * et en comparant les artefacts.
 *
 * Deux échecs possibles, tous deux voulus :
 *
 *  - deux artefacts **identiques** ⇒ le thème code des valeurs en dur, la
 *    cascade ne l'atteint pas ;
 *  - un contraste **sous le seuil AA** sur l'un des deux ⇒ le thème s'appuie
 *    sur une teinte qu'il ne devrait pas présumer.
 *
 * En consigne de revue seule, ce test ne serait pas fait dans la pratique.
 */
function themeCompileUnder(string $profile): string
{
    app(ApplyBrandProfile::class)($profile);

    // Les artefacts sont nommés par empreinte : on lit la chaîne CSS produite
    // plutôt que le fichier, pour ne dépendre ni du disque ni du nommage.
    return app(CompileDesignTokens::class)->buildCss(app(ResolveDesignTokens::class)());
}

/**
 * Luminance relative WCAG, puis rapport de contraste. Écrit ici plutôt
 * qu'importé : c'est trois lignes de norme, et une dépendance de plus pour
 * trois lignes serait un mauvais échange.
 */
function themeContrastRatio(string $hexA, string $hexB): float
{
    $luminance = static function (string $hex): float {
        $hex = ltrim(trim($hex), '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        $channels = array_map(static function (string $pair): float {
            $value = hexdec($pair) / 255;

            return $value <= 0.03928 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4;
        }, str_split(substr($hex, 0, 6), 2));

        return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
    };

    $a = $luminance($hexA);
    $b = $luminance($hexB);

    return (max($a, $b) + 0.05) / (min($a, $b) + 0.05);
}

it('produces a different stylesheet under two brand profiles', function () {
    $corporate = themeCompileUnder('corporate');
    $luxury = themeCompileUnder('luxury');

    expect($corporate)->not->toBe($luxury);
});

it('keeps text readable on both surfaces under every core brand profile', function (string $profile) {
    themeCompileUnder($profile);

    $tokens = app(ResolveDesignTokens::class)();

    $text = $tokens['colors']['text'];
    $background = $tokens['colors']['background'];
    $surface = $tokens['colors']['surface'];

    // 4.5:1 est le seuil AA pour du texte courant — celui du corps d'un
    // article, c'est-à-dire l'essentiel de ce que ce thème affiche.
    expect(themeContrastRatio($text, $background))->toBeGreaterThanOrEqual(4.5)
        ->and(themeContrastRatio($text, $surface))->toBeGreaterThanOrEqual(4.5);
})->with(['corporate', 'modern', 'elegant', 'luxury', 'education', 'medical', 'startup', 'minimal']);

/**
 * Le pendant statique du test ci-dessus : la feuille du thème ne doit contenir
 * aucune couleur à elle. Si elle en portait une, la compilation des tokens ne
 * la verrait pas — l'artefact changerait bien d'un profil à l'autre, et le
 * thème resterait pourtant en partie figé.
 */
it('maps its stylesheet onto tokens and never onto values', function () {
    $css = (string) File::get(dirname(__DIR__).'/resources/assets/css/app.css');

    // Hors commentaires : plusieurs d'entre eux citent la règle.
    $css = (string) preg_replace('#/\*.*?\*/#s', '', $css);

    expect($css)->not->toMatch('/#[0-9a-fA-F]{3,8}\b/')
        ->and($css)->not->toMatch('/\b(rgb|rgba|hsl|hsla|oklch)\(/');
});
