{{--
    Pagination numérotée (spec 19 §5.5).

    **Pourquoi le thème la fournit.** `links()` rend par défaut les vues de
    Laravel, écrites sur Tailwind mais pas sur les `--bb-*` : sans cette
    surcharge, une page d'archive resterait grise pendant que tout le reste
    suit le profil de marque, et le test du preset (§9.4) passerait sans rien
    voir. Elle est **nommée à chaque appel** de `links()` et jamais désignée
    par `Paginator::defaultView()` au provider : ce réglage est global à
    l'application et s'appliquerait donc aussi à l'admin, qui ne monte pas le
    namespace `theme::` — l'écran de contenus tombait alors sur « No hint path
    defined for [theme] » (§5.5, corrigé le 12 août 2026).

    Numérotée, jamais un « charger plus » : celui-ci suppose du JavaScript
    (§8.3). Ce sont des liens ordinaires, qui fonctionnent au clavier, au
    lecteur d'écran et sans script.

    `aria-current="page"` porte l'état courant plutôt que la seule couleur
    (§4.5) : la page active reste identifiable quand le contraste ne suffit
    pas.
--}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('theme::theme.pagination_label') }}">
        <ul class="flex flex-wrap items-center justify-center gap-xs">
            @if ($paginator->onFirstPage())
                <li>
                    <span class="rounded-md border border-border px-sm py-xs text-sm text-muted" aria-disabled="true">
                        {{ __('theme::theme.pagination_previous') }}
                    </span>
                </li>
            @else
                <li>
                    <a
                        href="{{ $paginator->previousPageUrl() }}"
                        rel="prev"
                        class="rounded-md border border-border px-sm py-xs text-sm text-foreground"
                    >
                        {{ __('theme::theme.pagination_previous') }}
                    </a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li>
                        <span class="px-sm py-xs text-sm text-muted" aria-hidden="true">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span
                                    aria-current="page"
                                    class="rounded-md border border-primary bg-primary px-sm py-xs text-sm text-background"
                                >
                                    {{ $page }}
                                </span>
                            @else
                                <a
                                    href="{{ $url }}"
                                    class="rounded-md border border-border px-sm py-xs text-sm text-foreground"
                                    aria-label="{{ __('theme::theme.pagination_go_to', ['page' => $page]) }}"
                                >
                                    {{ $page }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li>
                    <a
                        href="{{ $paginator->nextPageUrl() }}"
                        rel="next"
                        class="rounded-md border border-border px-sm py-xs text-sm text-foreground"
                    >
                        {{ __('theme::theme.pagination_next') }}
                    </a>
                </li>
            @else
                <li>
                    <span class="rounded-md border border-border px-sm py-xs text-sm text-muted" aria-disabled="true">
                        {{ __('theme::theme.pagination_next') }}
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
