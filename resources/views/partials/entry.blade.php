{{--
    Fiche d'une entrée — noyau partagé par `single`, `page` et le mode « page
    statique » de `home` (spec 19 §5.4, §5.6).

    Le gabarit est le même partout ; ce qui change tient en deux interrupteurs,
    parce que la différence entre une fiche et une page est **éditoriale** et
    non structurelle : une page statique n'est pas datée, et ne porte pas de
    taxonomies.

        $showMeta       date et auteur          (défaut : oui)
        $showTaxonomies relations many_to_many  (défaut : oui)
        $narrow         mesure de lecture       (défaut : non)

    Aucune clé de champ n'apparaît ici : `<x-baobab::field.auto>` résout les
    rôles depuis le blueprint (§5.10). C'est ce qui permet à ce partial de
    servir n'importe quel Content Type sans rien savoir de lui.

    Absents volontairement (§5.4) : partage social, contenus liés, navigation
    précédent/suivant. Chacun est une hypothèse éditoriale qu'un échafaudage
    n'a pas à faire à la place de l'intégrateur.
--}}
<article class="mx-auto {{ ($narrow ?? false) ? 'max-w-3xl' : 'max-w-5xl' }} px-md py-xl">
    <x-baobab::field.auto :entry="$entry" role="image" class="mb-lg" />

    <h1 class="font-display text-3xl font-bold tracking-tight text-balance text-foreground">{{ $title }}</h1>

    @if ($showMeta ?? true)
        <p class="mt-sm flex flex-wrap items-center gap-sm text-sm text-muted">
            @if ($entry->getAttribute('published_at') !== null)
                <time datetime="{{ $entry->getAttribute('published_at')->toDateString() }}">
                    {{ $entry->getAttribute('published_at')->isoFormat('LL') }}
                </time>
            @endif

            <x-baobab::entry-author :entry="$entry" />
        </p>
    @endif

    <div class="measure mt-lg text-foreground">
        <x-baobab::field.auto :entry="$entry" role="body" />
    </div>

    <x-baobab::field.auto :entry="$entry" role="rest" class="mt-lg" />

    @if ($showTaxonomies ?? true)
        <x-baobab::field.auto :entry="$entry" role="relations" class="mt-lg" />
    @endif
</article>
