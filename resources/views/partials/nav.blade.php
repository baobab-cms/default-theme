{{--
    Navigation mobile (spec 19 §4.6, §8.3).

    Accordéon natif `<details>` / `<summary>` : focusable et tabulable par
    construction, ouvert et fermé par le navigateur lui-même. Le thème par
    défaut ne charge pas une ligne de JavaScript côté public — ce n'est pas
    qu'une économie de budget, c'est une contrainte saine pour un code de
    référence : un intégrateur qui a besoin de JS l'ajoutera en connaissance de
    cause.

    Masqué au-delà de `md`, où l'en-tête affiche la navigation en clair.
--}}
<details class="md:hidden">
    <summary
        class="rounded-md border border-border px-sm py-xs text-sm text-foreground"
        aria-label="{{ __('theme::theme.main_navigation') }}"
    >
        {{ __('theme::theme.menu') }}
    </summary>

    <x-baobab::menu
        location="primary"
        class="absolute right-0 left-0 z-40 border-b border-border bg-surface px-md py-sm"
        aria-label="{{ __('theme::theme.main_navigation') }}"
    />
</details>
