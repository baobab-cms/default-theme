<?php

declare(strict_types=1);

use Baobab\DefaultTheme\Providers\DefaultThemeServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\View;

uses(RefreshDatabase::class);

/**
 * Rendu des sept templates (spec 19 §9.1) et règle des états vides (§5.9).
 *
 * **Pourquoi ces tests existent.** Le thème par défaut est le seul que la CI
 * puisse exercer de bout en bout ; ne pas le tester revient à ne jamais tester
 * le contrat de thème lui-même (§9.1). Un template qui casse ne se voit sinon
 * qu'en navigateur, sur le site de quelqu'un.
 *
 * Le namespace `theme::` est monté à la main : en temps normal c'est le
 * middleware `ResolveActiveTheme` qui le fait, par requête et pour le thème
 * actif — or ces tests rendent les vues **sans passer par une requête
 * publique**, ce qui est précisément ce qui les rend rapides et indépendants
 * d'une base de contenu.
 */
beforeEach(function (): void {
    View::replaceNamespace('theme', [dirname(__DIR__).'/resources/views']);
    View::getFinder()->flush();

    // Le provider du thème est enregistré ici comme le cycle de vie des
    // modules le fait pour un thème **actif** : sans lui, ni traductions, ni
    // `config('theme')`, ni le view composer qui fournit `$siteName` à
    // l'en-tête et au héros. C'est exactement ce qui manque à la
    // prévisualisation (suivi n° 125), et le rendre explicite ici évite qu'un
    // test vert masque ce défaut-là.
    app()->register(DefaultThemeServiceProvider::class);
});

/**
 * @param  list<array<string, mixed>>  $items
 */
function themePaginator(array $items = [], int $perPage = 15, int $total = 0): LengthAwarePaginator
{
    return new LengthAwarePaginator($items, $total, $perPage, 1, ['path' => '/articles']);
}

it('renders every template of the contract', function (string $template, array $data) {
    $html = view("theme::templates.{$template}", $data)->render();

    expect($html)->toContain('<!DOCTYPE html>')
        ->and($html)->toContain('</html>');
})->with([
    'index (sans rien)' => ['index', []],
    'index (liste vide)' => ['index', ['title' => 'Articles', 'entries' => null]],
    'home (défaut)' => ['home', []],
    'search (sans requête)' => ['search', ['query' => '', 'results' => []]],
    '404' => ['404', []],
]);

it('renders the homepage hero on the default reading mode', function () {
    $html = view('theme::templates.home', [])->render();

    expect($html)->toContain(e(__('theme::theme.home_hero_body')))
        ->and($html)->toContain(e(__('theme::theme.home_hero_action')));
});

/**
 * Régression — `@elseisset` n'est pas une directive Blade réelle (ni un
 * mot-clé natif, ni enregistrée nulle part dans le dépôt) : elle survivait à
 * la compilation comme texte littéral, ce qui laissait les deux `@include`
 * dans la MÊME branche compilée (`if(isset($entry))`) au lieu de s'exclure.
 * Toute page en mode « page statique » incluait donc aussi `entry-list` avec
 * `$entries` non défini, et `count(null)` y est une `TypeError` depuis
 * PHP 8 — reproduit en recette sur hébergement mutualisé réel le
 * 4 septembre 2026 (suivi n° 256), jamais vu ici parce qu'aucun test
 * n'avait encore rendu ce template avec `$entry` renseigné. Corrigé en
 * `@if`/`@elseif`/`@else`, seule forme où Blade exclut vraiment les branches.
 */
it('renders only the static-page branch when an entry is set, never entry-list too', function () {
    $entry = new class extends Model
    {
        protected $guarded = [];
    };
    $entry->forceFill(['title' => 'Bienvenue', 'published_at' => null]);

    $html = view('theme::templates.home', ['entry' => $entry, 'title' => 'Bienvenue'])->render();

    expect($html)->toContain('Bienvenue')
        ->and($html)->not->toContain('elseisset')
        ->and($html)->not->toContain(e(__('theme::theme.archive_empty')));
});

it('renders the not found page without excuse, with a way out', function () {
    $html = view('theme::templates.404', [])->render();

    expect($html)->toContain(e(__('theme::theme.not_found_title')))
        ->and($html)->toContain(e(__('theme::theme.search_submit')))
        ->and($html)->toContain(e(__('theme::theme.back_to_home')));
});

/**
 * §5.9 — la règle des états vides, deux applications, aucune exception :
 * un **vide de contenu** parle, un **vide de chrome** disparaît.
 */
it('speaks when an archive has nothing published', function () {
    $html = view('theme::partials.entry-list', [
        'entries' => themePaginator(),
        'heading' => 'Articles',
    ])->render();

    expect($html)->toContain(e(__('theme::theme.archive_empty')));
});

it('speaks when the universal fallback has nothing to show', function () {
    $html = view('theme::templates.index', ['title' => 'Articles', 'entries' => themePaginator()])->render();

    expect($html)->toContain(e(__('theme::theme.nothing_here_body')));
});

it('invites a search rather than leaving a dead end', function () {
    $withoutQuery = view('theme::templates.search', ['query' => '', 'results' => []])->render();
    $withQuery = view('theme::templates.search', ['query' => 'baobab', 'results' => []])->render();

    expect($withoutQuery)->toContain(e(__('theme::theme.search_invite')))
        ->and($withQuery)->toContain(e(__('theme::theme.search_empty_hint')))
        ->and($withQuery)->toContain('baobab');
});

/**
 * Un vide de **chrome** ne parle pas : sans menu `primary` assigné, l'en-tête
 * se réduit à l'identité du site — pas de trou, pas de message d'erreur, et
 * surtout aucun diagnostic technique servi au visiteur (§4.3, §6.5).
 */
it('says nothing to the visitor when the chrome is empty', function () {
    $html = view('theme::templates.404', [])->render();

    expect($html)->not->toContain('Aucun menu')
        ->and($html)->not->toContain('theme::theme.')
        ->and($html)->not->toContain('Aucun thème actif');
});

it('never renders a translation key it forgot to define', function (string $template, array $data) {
    expect(view("theme::templates.{$template}", $data)->render())->not->toContain('theme::theme.');
})->with([
    ['index', []],
    ['home', []],
    ['404', []],
    ['search', ['query' => '', 'results' => []]],
]);
