@extends('layouts.admin')

@section('title', 'Administration — Tickets')

@section('content')
<div class="admin-header">
    <h1>Tickets support</h1>
    <span class="badge">{{ $tickets->total() }} ticket(s)</span>
</div>

{{-- Filtres par statut --}}
<div class="filter-bar">
    <a href="{{ route('admin.tickets.index') }}"
       class="filter-btn {{ !$status ? 'active' : '' }}">Tous</a>
    @foreach(['ouvert' => 'Ouverts', 'en_cours' => 'En cours', 'resolu' => 'Résolus', 'ferme' => 'Fermés'] as $val => $label)
        <a href="{{ route('admin.tickets.index', ['status' => $val]) }}"
           class="filter-btn {{ $status === $val ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
</div>

@if($tickets->isEmpty())
    <p class="empty-state">Aucun ticket pour ce filtre.</p>
@else
<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Sujet</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->id }}</td>
                <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                <td>
                    {{ $ticket->name }}<br>
                    <small class="text-muted">{{ $ticket->email }}</small>
                </td>
                <td>{{ $ticket->categoryLabel() }}</td>
                <td>{{ Str::limit($ticket->subject, 50) }}</td>
                <td><span class="status-badge status-{{ $ticket->status }}">{{ $ticket->statusLabel() }}</span></td>
                <td><a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm">Voir</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $tickets->links() }}
@endif
@endsection
