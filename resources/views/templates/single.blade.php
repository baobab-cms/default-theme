{{--
    Fiche d'une entrée (spec 19 §5.4).

    Noyau strict : titre, date, contenu. Conditionnels au blueprint : image
    mise en avant, auteur, taxonomies — rendus seulement si le type les porte,
    et c'est `<x-baobab::field.auto>` qui le sait, pas ce template.

    Tout le gabarit vit dans `partials/entry` : `single`, `page` et le mode
    « page statique » de `home` sont la même fiche à deux interrupteurs près.
    Trois copies auraient divergé à la première correction.
--}}
@extends('theme::layouts.app')

@section('content')
    @include('theme::partials.entry')
@endsection
