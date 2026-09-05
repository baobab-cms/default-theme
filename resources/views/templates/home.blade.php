{{--
    Accueil (spec 19 §5.3).

    **Trois formes, pas une.** `home` est résolu en premier dans les *trois*
    modes de `RenderHomepage` : page statique (`entry` + `title`), derniers
    contenus (`entries` + `title`), et défaut (rien du tout). Un template
    réduit au héros masquerait donc les derniers contenus dès que Réglages >
    Lecture les demande — il se substituerait au réglage, ce que §5.3 interdit
    explicitement. Le héros est le visage du **mode par défaut**, pas celui de
    tous les modes.

    Héros typographique : titre à l'échelle maximale, chapeau, un lien. Pas
    d'image obligatoire (direction-visuelle §12) — un thème par défaut qui en
    exigerait une transformerait chaque installation neuve en chantier.
--}}
@extends('theme::layouts.app')

@section('content')
    @if (isset($entry))
        {{-- Mode « page statique » : le réglage a désigné une entrée. --}}
        @include('theme::partials.entry', ['showMeta' => false, 'showTaxonomies' => false])
    @elseif (isset($entries))
        {{-- Mode « derniers contenus ». --}}
        @include('theme::partials.entry-list', ['heading' => $title])
    @else
        <div class="mx-auto max-w-5xl px-md py-xl">
            <h1 class="font-display text-3xl font-bold tracking-tight text-balance text-foreground">
                {{ $siteName }}
            </h1>

            <p class="measure mt-md text-lg text-muted">{{ __('theme::theme.home_hero_body') }}</p>

            <a
                href="{{ route('baobab.search') }}"
                class="mt-lg inline-block rounded-md border border-primary bg-primary px-md py-xs text-base text-background"
            >
                {{ __('theme::theme.home_hero_action') }}
            </a>
        </div>
    @endif
@endsection
