{{--
    En-tête (spec 19 §4.2, §4.3).

    Il porte l'identité du **site** — le logo de branding si l'administrateur en
    a réglé un, sinon le nom du site. La mention Baobab, elle, ne vit qu'au pied
    de page : les deux identités ne se confondent pas.

    `$siteName` et `$siteLogo` sont fournis par un view composer déclaré dans
    DefaultThemeServiceProvider. Aucune requête Eloquent ici : c'est un interdit
    du projet, et un thème de référence qui le transgresserait enseignerait
    exactement ce qu'il ne faut pas faire.

    Sans menu `primary` assigné, `<x-baobab::menu>` ne rend rien : l'en-tête se
    réduit à l'identité du site, sans trou visuel ni message d'erreur pour le
    visiteur (§4.3, règle des états vides §5.9). L'invitation à créer un menu
    n'existe que dans la bande admin, pour qui peut agir.
--}}
<header class="border-b border-border bg-surface">
    <div class="mx-auto flex max-w-5xl items-center justify-between gap-lg px-md py-md">
        <a href="{{ url('/') }}" class="flex items-center gap-sm no-underline">
            @if ($siteLogo)
                <x-baobab::img :media="$siteLogo" :alt="$siteName" loading="eager" class="h-8 w-auto" />
            @else
                <span class="font-display text-xl font-bold tracking-tight text-foreground">{{ $siteName }}</span>
            @endif
        </a>

        {{-- Navigation large : visible dès le palier md. --}}
        <x-baobab::menu
            location="primary"
            class="hidden md:block"
            aria-label="{{ __('theme::theme.main_navigation') }}"
        />

        @include('theme::partials.nav')
    </div>
</header>
