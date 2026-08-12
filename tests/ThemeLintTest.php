<?php

declare(strict_types=1);

use Baobab\Modules\ModuleManifest;
use Baobab\Themes\Validation\ThemeValidator;
use Illuminate\Support\Facades\File;

/**
 * Le validateur de thèmes, appliqué au thème par défaut (spec 19 §9.1,
 * spec 17 §6.1).
 *
 * **Ce que ce test garantit, et pourquoi il est bloquant.** Le thème par
 * défaut est la référence de conformité (§9.2) : la documentation le cite en
 * exemple, et le validateur peut s'appuyer sur ses conventions pour ses
 * propres règles. Une référence qui ne passerait pas son propre validateur
 * enseignerait exactement ce qu'il faut éviter.
 *
 * `assertValid()` est aussi ce qu'`InstallModule` appelle : un thème qui échoue
 * ici ne s'installe pas du tout. Le test échoue donc **avant** le serveur de
 * quelqu'un.
 */
it('passes its own theme validator without a single violation', function () {
    $path = dirname(__DIR__);
    $manifest = ModuleManifest::fromJson((string) File::get($path.'/module.json'));

    $violations = app(ThemeValidator::class)->validate($manifest, $path);

    $readable = array_map(
        static fn (object $violation): string => (string) $violation,
        $violations,
    );

    expect($readable)->toBe([]);
});

/**
 * Interdits du projet, vérifiés sur les vues plutôt que déduits : `@php` et
 * `<?php` dans une vue de thème (CLAUDE.md, spec 03 §1). Le validateur les
 * couvre déjà ; ce test vise le cas où la vue serait ajoutée hors des chemins
 * qu'il inspecte.
 */
it('never opens a PHP block inside a view', function () {
    $offenders = [];

    foreach (File::allFiles(dirname(__DIR__).'/resources/views') as $file) {
        $contents = (string) File::get($file->getPathname());

        if (str_contains($contents, '@php') || str_contains($contents, '<?php')) {
            $offenders[] = $file->getFilename();
        }
    }

    expect($offenders)->toBe([]);
});
