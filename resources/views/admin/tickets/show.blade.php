@extends('layouts.admin')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
<div class="admin-header">
    <h1>Ticket #{{ $ticket->id }}</h1>
    <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">← Retour</a>
</div>

<div class="ticket-detail">
    <div class="ticket-meta">
        <div class="meta-row">
            <span class="meta-label">De</span>
            <span>{{ $ticket->name }} — <a href="mailto:{{ $ticket->email }}">{{ $ticket->email }}</a></span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Catégorie</span>
            <span>{{ $ticket->categoryLabel() }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Sujet</span>
            <span>{{ $ticket->subject }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Date</span>
            <span>{{ $ticket->created_at->format('d/m/Y à H:i') }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Statut</span>
            <span class="status-badge status-{{ $ticket->status }}">{{ $ticket->statusLabel() }}</span>
        </div>
    </div>

    <div class="ticket-body">
        <h3>Message</h3>
        <p>{{ $ticket->message }}</p>
    </div>

    {{-- Formulaire de changement de statut --}}
    <form method="POST" action="{{ route('admin.tickets.status', $ticket) }}" class="status-form">
        @csrf
        @method('PATCH')
        <label for="status">Changer le statut :</label>
        <select name="status" id="status">
            @foreach(['ouvert' => 'Ouvert', 'en_cours' => 'En cours', 'resolu' => 'Résolu', 'ferme' => 'Fermé'] as $val => $label)
                <option value="{{ $val }}" {{ $ticket->status === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn">Mettre à jour</button>
    </form>
</div>
@endsection
