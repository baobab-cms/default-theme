{{--
    Page statique (spec 19 §5.6).

    Même gabarit que `single`, **mesure plus étroite**, et sans métadonnées :
    ni date, ni auteur, ni taxonomies. La différence est éditoriale et non
    structurelle — une page statique n'est pas datée, et ne se range pas.
--}}
@extends('theme::layouts.app')

@section('content')
    @include('theme::partials.entry', ['showMeta' => false, 'showTaxonomies' => false, 'narrow' => true])
@endsection
