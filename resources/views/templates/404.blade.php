{{--
    Page introuvable (spec 19 §5.8).

    Ton sobre et factuel, **sans humour ni excuse** : ce qui s'est passé, un
    champ de recherche, un lien d'accueil. C'est la règle d'écriture déjà tenue
    dans l'admin (direction-visuelle §10.2) — une erreur ne s'excuse pas et
    n'est jamais vague. Un 404 drôle fait perdre une seconde à qui cherche
    quelque chose, et deux à qui revient.

    Le champ de recherche est ici parce que c'est le seul endroit où il répond
    à un besoin immédiat : la page demandée n'existe pas, celle qu'on cherchait
    existe peut-être.

    Aucune donnée n'est passée à ce template par le Core : rien n'y est lu de
    l'entrée absente.
--}}
@extends('theme::layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-md py-xl">
        <h1 class="font-display text-3xl font-bold tracking-tight text-balance text-foreground">
            {{ __('theme::theme.not_found_title') }}
        </h1>

        <p class="measure mt-md text-muted">{{ __('theme::theme.not_found_body') }}</p>

        <form
            method="GET"
            action="{{ route('baobab.search') }}"
            role="search"
            class="measure mt-lg flex flex-col gap-sm sm:flex-row"
        >
            <label for="not-found-search" class="sr-only">{{ __('theme::theme.search_label') }}</label>

            <input
                id="not-found-search"
                type="search"
                name="q"
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

        <a href="{{ url('/') }}" class="mt-lg inline-block text-foreground underline underline-offset-4">
            {{ __('theme::theme.back_to_home') }}
        </a>
    </div>
@endsection
