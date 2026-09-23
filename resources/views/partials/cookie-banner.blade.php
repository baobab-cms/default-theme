{{--
    Bannière de consentement aux cookies (support `cookie-banner`, spec 19 §3.4,
    spec 16 §3.2), rendue par `<x-baobab::consent-banner />` à la place de la
    bannière Core.

    Présentation pure, comme tout le thème (§8.3) : pas une ligne de
    JavaScript ici. Le comportement appartient au script du Core, qui ne lit
    que les attributs `data-baobab-consent-*` — ils forment le contrat et ne
    se renomment pas. Le composant n'est même pas rendu sans traceur déclaré.

    Refuser pèse exactement autant qu'accepter : même bordure, même taille,
    même rang. Profondeur par la bordure, pas par l'ombre (§4.5).

    Variables : $categories (clé, libellé, description de chaque catégorie
    proposée), $necessary (libellé, description).
--}}
<div
    class="fixed inset-x-md bottom-md z-50 mx-auto max-w-2xl rounded-md border border-border bg-background p-md text-sm text-foreground"
    data-baobab-consent-banner
    hidden
    role="dialog"
    aria-modal="false"
    aria-labelledby="consent-title"
    aria-describedby="consent-intro"
>
    <h2 id="consent-title" class="font-display text-base font-semibold">
        {{ __('theme::theme.cookie_banner_title') }}
    </h2>

    <p id="consent-intro" class="mt-xs text-muted">
        {{ __('theme::theme.cookie_banner_intro') }}
    </p>

    <fieldset id="consent-panel" class="mt-sm" data-baobab-consent-panel hidden>
        <legend class="sr-only">{{ __('theme::theme.cookie_banner_customize') }}</legend>

        <div class="grid grid-cols-[auto_1fr] gap-x-xs border-t border-border py-xs">
            <input type="checkbox" id="consent-necessary" checked disabled>
            <label for="consent-necessary" class="font-medium">
                {{ $necessary['label'] }} — {{ __('theme::theme.cookie_banner_always_active') }}
            </label>
            <p class="col-start-2 text-muted">{{ $necessary['description'] }}</p>
        </div>

        @foreach ($categories as $category)
            <div class="grid grid-cols-[auto_1fr] gap-x-xs border-t border-border py-xs">
                <input type="checkbox" id="consent-{{ $category['key'] }}" data-baobab-consent-category="{{ $category['key'] }}">
                <label for="consent-{{ $category['key'] }}" class="font-medium">{{ $category['label'] }}</label>
                <p class="col-start-2 text-muted">{{ $category['description'] }}</p>
            </div>
        @endforeach

        <button type="button" class="mt-xs rounded-md border border-foreground px-sm py-xs" data-baobab-consent-action="save">
            {{ __('theme::theme.cookie_banner_save') }}
        </button>
    </fieldset>

    <div class="mt-sm flex flex-wrap gap-xs">
        <button type="button" class="rounded-md border border-foreground px-sm py-xs" data-baobab-consent-action="reject-all">
            {{ __('theme::theme.cookie_banner_reject_all') }}
        </button>

        <button type="button" class="rounded-md border border-foreground px-sm py-xs" data-baobab-consent-action="accept-all">
            {{ __('theme::theme.cookie_banner_accept_all') }}
        </button>

        <button
            type="button"
            class="rounded-md border border-border px-sm py-xs text-muted"
            data-baobab-consent-action="customize"
            aria-expanded="false"
            aria-controls="consent-panel"
        >
            {{ __('theme::theme.cookie_banner_customize') }}
        </button>
    </div>
</div>
