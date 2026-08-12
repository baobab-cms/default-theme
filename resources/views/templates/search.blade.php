{{--
    Recherche (spec 19 §5.7) — deux états distincts, souvent confondus :

    - **sans requête** : le champ seul, avec une invitation explicite ;
    - **avec une requête sans résultat** : la requête rappelée à l'écran, une
      piste de reformulation, un lien vers l'accueil.

    Jamais un cul-de-sac silencieux. C'est un vide de **contenu** : il parle
    (§5.9).

    Le champ vit ici et nulle part ailleurs — pas de champ permanent en en-tête
    (§4.4), qui imposerait une largeur et un comportement mobile que
    l'échafaudage n'a pas à trancher.
--}}
@extends('theme::layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-md py-xl">
        <h1 class="font-display text-3xl font-bold tracking-tight text-foreground">
            {{ $query === ''
                ? __('theme::theme.search_title')
                : __('theme::theme.search_results_for', ['query' => $query]) }}
        </h1>

        <form
            method="GET"
            action="{{ route('baobab.search') }}"
            role="search"
            class="measure mt-lg flex flex-col gap-sm sm:flex-row"
        >
            <label for="theme-search" class="sr-only">{{ __('theme::theme.search_label') }}</label>

            <input
                id="theme-search"
                type="search"
                name="q"
                value="{{ $query }}"
                placeholder="{{ __('theme::theme.search_placeholder') }}"
                class="flex-1 rounded-md border border-border bg-background px-sm py-xs text-base text-foreground"
            >

            <button
                type="submit"
                class="rounded-md border border-primary bg-primary px-md py-xs text-base text-background"
            >
                {{ __('theme::theme.search_submit') }}
            </button>
        </form>

        @forelse ($results as $group)
            <section class="mt-lg">
                <h2 class="font-display text-xl font-medium text-foreground">{{ $group['label'] }}</h2>

                <ul class="mt-sm flex flex-col gap-sm">
                    @foreach ($group['results']->items as $item)
                        <li class="border-b border-border pb-sm last:border-b-0">
                            <a href="{{ $item->url }}" class="text-foreground underline underline-offset-4">
                                {{ $item->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @empty
            <div class="measure mt-lg flex flex-col gap-sm">
                @if ($query === '')
                    <p class="text-muted">{{ __('theme::theme.search_invite') }}</p>
                @else
                    <p class="text-foreground">{{ __('theme::theme.search_empty_title', ['query' => $query]) }}</p>
                    <p class="text-muted">{{ __('theme::theme.search_empty_hint') }}</p>

                    <a href="{{ url('/') }}" class="text-foreground underline underline-offset-4">
                        {{ __('theme::theme.back_to_home') }}
                    </a>
                @endif
            </div>
        @endforelse
    </div>
@endsection
