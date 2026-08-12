{{--
    Archive d'un Content Type (spec 19 §5.5) — cartes sommaires et pagination
    numérotée, le tout dans `partials/entry-list`, partagé avec le mode
    « derniers contenus » de `home`.
--}}
@extends('theme::layouts.app')

@section('content')
    @include('theme::partials.entry-list', ['heading' => $title])
@endsection
