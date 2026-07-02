{{-- Affichage d'une page d'information avec sidebar de navigation --}}
@extends('layouts.app')

@section('title', $page->title . ' — CESIZen')

@section('content')
<div class="container">
    <div class="content-with-sidebar">
        {{-- Sidebar : liste de toutes les pages publiées pour la navigation --}}
        <aside class="sidebar">
            <h3>Pages</h3>
            <ul class="page-list">
                @foreach($pages as $p)
                    {{-- La page courante est mise en valeur via la classe "active" --}}
                    <li class="{{ $p->id === $page->id ? 'active' : '' }}">
                        <a href="{{ route('information.show', $p->slug) }}">{{ $p->title }}</a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <article class="content-area">
            <h1>{{ $page->title }}</h1>
            {{-- nl2br convertit les sauts de ligne en <br>, e() échappe le HTML --}}
            <div class="page-content">{!! nl2br(e($page->content)) !!}</div>
        </article>
    </div>
</div>
@endsection
