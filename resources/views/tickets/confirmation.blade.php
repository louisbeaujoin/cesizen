@extends('layouts.app')

@section('title', 'CESIZen — Ticket envoyé')

@section('content')
<div class="container">
    <div class="confirmation-card">
        <div class="confirmation-icon">✅</div>
        <h1>Ticket envoyé !</h1>
        <p>Merci pour votre message. Notre équipe l'a bien reçu et vous répondra à l'adresse e-mail indiquée dans les plus brefs délais.</p>
        <div class="confirmation-actions">
            <a href="{{ route('home') }}" class="btn">Retour à l'accueil</a>
            <a href="{{ route('tickets.create') }}" class="btn btn-secondary">Nouveau ticket</a>
        </div>
    </div>
</div>
@endsection
