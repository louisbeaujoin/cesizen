@extends('layouts.app')

@section('title', 'CESIZen — Soumettre un ticket')

@section('content')
<div class="container">
    <div class="ticket-form-wrapper">
        <h1>Soumettre un ticket</h1>
        <p class="ticket-intro">Un problème, une question ou une suggestion ? Remplissez ce formulaire, notre équipe vous répondra par e-mail.</p>

        <form method="POST" action="{{ route('tickets.store') }}" class="ticket-form">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="name">Votre nom <span class="required">*</span></label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', auth()->user()?->name) }}"
                           placeholder="Jean Dupont" required maxlength="100">
                    @error('name')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="email">Votre e-mail <span class="required">*</span></label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email', auth()->user()?->email) }}"
                           placeholder="jean@exemple.fr" required maxlength="150">
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category">Catégorie <span class="required">*</span></label>
                    <select id="category" name="category" required>
                        <option value="">— Choisir —</option>
                        <option value="bug"        {{ old('category') === 'bug'        ? 'selected' : '' }}>🐛 Bug / Erreur</option>
                        <option value="suggestion" {{ old('category') === 'suggestion' ? 'selected' : '' }}>💡 Suggestion</option>
                        <option value="question"   {{ old('category') === 'question'   ? 'selected' : '' }}>❓ Question</option>
                        <option value="autre"      {{ old('category') === 'autre'      ? 'selected' : '' }}>📝 Autre</option>
                    </select>
                    @error('category')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="subject">Sujet <span class="required">*</span></label>
                    <input type="text" id="subject" name="subject"
                           value="{{ old('subject') }}"
                           placeholder="Résumé du problème ou de la demande" required maxlength="200">
                    @error('subject')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-group">
                <label for="message">Description <span class="required">*</span></label>
                <textarea id="message" name="message" rows="6" required minlength="20" maxlength="2000"
                          placeholder="Décrivez le problème ou la demande en détail...">{{ old('message') }}</textarea>
                @error('message')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Envoyer le ticket</button>
                <a href="{{ route('home') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
