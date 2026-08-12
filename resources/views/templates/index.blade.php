{{--
    Repli universel du thème (spec 19 §5.2).

    `index` est le seul template obligatoire du contrat (spec 03 §2) et le repli
    de toute la hiérarchie : il peut être atteint avec une entrée
    (`$entry` + `$title`), avec une liste (`$entries` + `$title`), ou sans rien.
    Il absorbe les trois plutôt que d'en supposer une.

    Il **rend du contenu**, jamais un diagnostic. La version 0.1 de la spec y
    plaçait un message expliquant qu'aucun template plus spécifique n'avait été
    trouvé : c'était servir à un visiteur une information qu'il ne peut pas
    utiliser, précisément ce que la règle des états vides interdit ailleurs. Le
    diagnostic existe toujours, mais là où il sert — dans la bande admin, pour
    quelqu'un qui peut agir.

    Composition volontairement la plus neutre possible : c'est un repli, pas une
    mise en page.
--}}
@extends('theme::layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-md py-xl">
        <h1 class="font-display text-3xl font-bold tracking-tight text-foreground">
            {{ $title ?? __('theme::theme.nothing_here_title') }}
        </h1>

        @isset($entries)
            @if (count($entries) > 0)
                <ul class="mt-lg flex flex-col gap-md">
                    @foreach ($entries as $item)
                        <li class="border-b border-border pb-md last:border-b-0">
                            {{ $item->getAttribute('title') ?? $item->getAttribute('slug') }}
                        </li>
                    @endforeach
                </ul>

                <div class="mt-lg">
                    {{ $entries->links() }}
                </div>
            @else
                {{-- Un vide de contenu parle (§5.9) : il invite à agir. --}}
                <p class="measure mt-md text-muted">{{ __('theme::theme.nothing_here_body') }}</p>
            @endif
        @else
            @empty($title)
                <p class="measure mt-md text-muted">{{ __('theme::theme.nothing_here_body') }}</p>
            @endempty
        @endisset
    </div>
@endsection
