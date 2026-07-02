{{-- Liste de toutes les pages d'information publiées --}}
@extends('layouts.app')

@section('title', 'Informations — CESIZen')

@section('content')
<div class="container">
    <h1>Informations sur la santé mentale</h1>

    {{-- Affiche la liste ou un message si aucune page n'est publiée --}}
    @if($pages->isEmpty())
        <p>Aucune information disponible pour le moment.</p>
    @else
        <ul class="page-list">
            @foreach($pages as $page)
                <li>
                    <a href="{{ route('information.show', $page->slug) }}">{{ $page->title }}</a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
