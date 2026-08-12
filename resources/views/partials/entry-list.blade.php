{{--
    Liste de cartes sommaires — partagée par `archive` et par le mode
    « derniers contenus » de `home` (spec 19 §5.5).

        $entries  paginateur du Core
        $heading  titre de la liste

    Carte : image si le type en porte une, titre lié, date, extrait. **Aucun
    filtre visible** : filtrer est une capacité du Core (spec 11), pas une
    opinion d'affichage que le thème par défaut a à imposer.

    Pagination **numérotée**, jamais un « charger plus » — celui-ci suppose du
    JavaScript, dont ce thème n'a pas une ligne côté public (§8.3). Les vues de
    pagination appartiennent au thème et sont écrites sur les tokens : sans
    elles, `links()` rendrait celles de Laravel, qui ignorent les `--bb-*`, et
    la liste resterait grise pendant que tout le reste suit le profil de marque.

    La vue est **nommée à l'appel**, jamais désignée par défaut au provider :
    `Paginator::defaultView()` est global à l'application, donc il s'appliquerait
    aussi à l'admin — qui ne monte pas le namespace `theme::` et n'a aucune
    raison de le faire. Une pagination de thème posée en défaut y provoque
    « No hint path defined for [theme] » (§5.5, corrigé le 12 août 2026).

    Ni le titre ni l'extrait ne sont lus par clé : `<x-baobab::entry-link>` et
    `<x-baobab::field.auto role="excerpt">` les résolvent depuis le blueprint.
--}}
<div class="mx-auto max-w-5xl px-md py-xl">
    <h1 class="font-display text-3xl font-bold tracking-tight text-balance text-foreground">{{ $heading }}</h1>

    @if (count($entries) > 0)
        <ul class="mt-lg flex flex-col gap-lg">
            @foreach ($entries as $item)
                <li class="border-b border-border pb-lg last:border-b-0">
                    <article class="flex flex-col gap-sm sm:flex-row sm:gap-md">
                        <x-baobab::field.auto
                            :entry="$item"
                            role="image"
                            class="shrink-0 sm:w-48 [&_img]:w-full [&_img]:rounded-md"
                        />

                        <div class="flex flex-col gap-xs">
                            <h2 class="font-display text-xl font-medium text-foreground">
                                <x-baobab::entry-link :entry="$item" class="underline underline-offset-4" />
                            </h2>

                            @if ($item->getAttribute('published_at') !== null)
                                <time
                                    datetime="{{ $item->getAttribute('published_at')->toDateString() }}"
                                    class="text-sm text-muted"
                                >
                                    {{ $item->getAttribute('published_at')->isoFormat('LL') }}
                                </time>
                            @endif

                            <x-baobab::field.auto :entry="$item" role="excerpt" class="measure text-muted" />
                        </div>
                    </article>
                </li>
            @endforeach
        </ul>

        <div class="mt-xl">
            {{ $entries->links('theme::pagination.numbered') }}
        </div>
    @else
        {{-- Un vide de contenu parle (§5.9) : il invite à agir. --}}
        <p class="measure mt-lg text-muted">{{ __('theme::theme.archive_empty') }}</p>
    @endif
</div>
