<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

/**
 * Arborescence du package (spec 19 §3.1), et ce que le contrat de thème
 * (spec 03) rend bloquant à l'installation.
 *
 * Ce test n'est pas de la paperasse : `InstallModule` appelle
 * `ThemeValidator::assertValid()`, et un fichier manquant y refuse
 * l'installation — pas seulement le lint. Un renommage malheureux se voit ici
 * plutôt que sur le serveur de quelqu'un.
 */
function themePath(string $relative = ''): string
{
    return dirname(__DIR__).($relative === '' ? '' : '/'.$relative);
}

it('ships every file the package layout declares', function (string $relative) {
    expect(File::exists(themePath($relative)))->toBeTrue("Fichier manquant : {$relative}");
})->with([
    'composer.json',
    'module.json',
    'package.json',
    'vite.config.js',
    'screenshot.png',
    'config/theme.php',
    'src/Providers/DefaultThemeServiceProvider.php',
    'resources/assets/css/app.css',
    'resources/lang/fr/theme.php',
    'resources/lang/en/theme.php',
    'resources/views/layouts/app.blade.php',
    'resources/views/partials/header.blade.php',
    'resources/views/partials/footer.blade.php',
    'resources/views/partials/nav.blade.php',
    'resources/views/pagination/numbered.blade.php',
    'public/build/.vite/manifest.json',
]);

it('ships the seven templates of the contract, and no more', function () {
    $templates = collect(File::files(themePath('resources/views/templates')))
        ->map(fn ($file): string => str_replace('.blade.php', '', $file->getFilename()))
        ->sort()
        ->values()
        ->all();

    // Ni `500` ni `maintenance` : le premier est un repli Core exclusif (§6.4),
    // la seconde une vue Core que ce thème choisit de ne pas surcharger (§3.4).
    expect($templates)->toBe(['404', 'archive', 'home', 'index', 'page', 'search', 'single']);
});

it('declares in its manifest exactly what it supports', function () {
    /** @var array<string, mixed> $manifest */
    $manifest = json_decode((string) File::get(themePath('module.json')), associative: true);

    expect($manifest['type'])->toBe('theme')
        ->and($manifest['theme']['supports'])->toBe(['search'])
        ->and($manifest['theme']['screenshot'])->toBe('screenshot.png')
        ->and($manifest['theme']['parent'])->toBeNull();
});

/**
 * §4.5 et §3.6 : le thème compile contre des variables, jamais contre des
 * valeurs. Une couleur écrite en dur serait invisible au test du preset (§9.4)
 * et casserait la promesse « changer le profil de marque re-teinte le thème »
 * au premier client.
 */
it('never writes a colour of its own, neither hexadecimal nor lightness-encoding', function () {
    $offenders = [];

    foreach (File::allFiles(themePath('resources/views')) as $file) {
        // Les commentaires Blade sont retirés avant l'examen : plusieurs
        // d'entre eux **énoncent la règle** en citant `bg-white` ou
        // `text-black`, et un garde qui se déclenche sur sa propre
        // documentation n'apprend rien à personne.
        $contents = (string) preg_replace('/\{\{--.*?--\}\}/s', '', (string) File::get($file->getPathname()));

        // Les valeurs hexadécimales, et les classes utilitaires qui encodent
        // la clarté (`bg-white`, `text-black`, `bg-gray-100`…).
        if (preg_match('/#[0-9a-fA-F]{3,8}\b/', $contents) === 1
            || preg_match('/\b(bg|text|border)-(white|black|gray|slate|zinc|neutral|stone)\b/', $contents) === 1) {
            $offenders[] = $file->getFilename();
        }
    }

    expect($offenders)->toBe([]);
});

/**
 * Le corollaire du défaut livré puis corrigé en Pass B2b (suivi n° 131) : un
 * thème ne pose aucun réglage **global** à l'application. `links()` sans
 * argument obligerait à désigner la vue de pagination par
 * `Paginator::defaultView()`, qui vaut aussi pour l'admin — laquelle ne monte
 * jamais le namespace `theme::`.
 */
it('never leaves a paginator call to a global default', function () {
    $offenders = [];

    foreach (File::allFiles(themePath('resources/views')) as $file) {
        $contents = (string) preg_replace('/\{\{--.*?--\}\}/s', '', (string) File::get($file->getPathname()));

        if (preg_match('/->links\(\s*\)/', $contents) === 1) {
            $offenders[] = $file->getFilename();
        }
    }

    expect($offenders)->toBe([]);
});

it('sets no application-wide default from its provider', function () {
    $provider = (string) File::get(themePath('src/Providers/DefaultThemeServiceProvider.php'));

    expect($provider)->not->toContain('Paginator::defaultView')
        ->and($provider)->not->toContain('Paginator::defaultSimpleView');
});
