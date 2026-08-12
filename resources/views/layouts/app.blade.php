{{--
    Layout racine du thème par défaut (spec 19 §4.1).

    En-tête (identité du site + navigation), zone de contenu, pied (menu
    `footer`, zone de widgets `footer`, mention Baobab), plus la bande admin du
    Core pour les visiteurs connectés qui ont les droits. Ni barre latérale, ni
    fil d'Ariane : le premier suppose une hiérarchie de contenu que le Core ne
    garantit pas pour tout Content Type, la seconde est une opinion de mise en
    page qu'un thème par défaut n'a pas à imposer.

    Aucune valeur hexadécimale, aucun nom de classe qui encode la clarté
    (`bg-white`, `text-black`), profondeur dite par la bordure et non par
    l'ombre, deux niveaux de surface au plus (§4.5) — quatre disciplines qui
    n'anticipent pas le mode sombre mais évitent de le rendre impossible.

    Pas une ligne de JavaScript côté public (§8.3).
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Rend le <title> et l'ensemble des balises SEO : ne pas en ajouter ici. --}}
    <x-baobab::seo-head />

    <x-baobab::design-tokens />
    <x-baobab::vite entry="app" />
</head>
{{-- La mesure de lecture vient de config('theme.reading_measure') (§3.8) : le
     CSS l'applique, il ne la connaît pas. --}}
<body
    class="flex min-h-full flex-col bg-background font-sans text-base text-foreground antialiased"
    style="--theme-measure: {{ config('theme.reading_measure', '68ch') }}"
>
    <a
        href="#content"
        class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 focus:rounded-md focus:border focus:border-border focus:bg-surface focus:px-4 focus:py-2"
    >
        {{ __('theme::theme.skip_to_content') }}
    </a>

    <x-baobab::admin-bar />

    @include('theme::partials.header')

    <main id="content" class="flex-1">
        @yield('content')
    </main>

    @include('theme::partials.footer')
</body>
</html>
