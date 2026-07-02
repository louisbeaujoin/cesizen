{{-- Page d'exercice de respiration guidé avec animation --}}
@extends('layouts.app')

@section('title', 'Exercice de respiration — CESIZen')

@section('css')
<link rel="stylesheet" href="{{ asset('css/breathing.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>
        @if($exercise)
            {{ $exercise->name }}
        @else
            Exercice personnalisé
        @endif
    </h1>

    <div class="exercise-info">
        <span>Inspiration : {{ $inspiration }}s</span>
        <span>Apnée : {{ $apnea }}s</span>
        <span>Expiration : {{ $expiration }}s</span>
        <span>Cycles : {{ $cycles }}</span>
    </div>

    <div class="breathing-widget" id="breathingWidget">
        <div class="breathing-circle" id="breathingCircle">
            <span class="breathing-text" id="breathingText">Prêt ?</span>
        </div>

        <div class="breathing-progress">
            <span id="cycleCounter">Cycle : 0 / {{ $cycles }}</span>
            <span id="timeRemaining"></span>
        </div>

        <div class="breathing-controls">
            <button class="btn" id="startBtn" onclick="startExercise()">Commencer</button>
            <button class="btn btn-secondary hidden" id="stopBtn" onclick="stopExercise()">Arrêter</button>
        </div>
    </div>

    <a href="{{ route('breathing.index') }}" class="btn btn-secondary">Retour aux exercices</a>
</div>
@endsection

@section('scripts')
<script>
    const CONFIG = {
        inspiration: {{ $inspiration }},
        apnea: {{ $apnea }},
        expiration: {{ $expiration }},
        totalCycles: {{ $cycles }},
        exerciseId: {{ $exercise ? $exercise->id : 'null' }},
        saveUrl: '{{ route("breathing.session.save") }}',
        csrfToken: '{{ csrf_token() }}',
        isAuthenticated: {{ auth()->check() ? 'true' : 'false' }}
    };

    let currentCycle = 0;
    let isRunning = false;
    let timeoutId = null;
    let startTime = null;

    const circle = document.getElementById('breathingCircle');
    const text = document.getElementById('breathingText');
    const cycleCounter = document.getElementById('cycleCounter');
    const timeRemaining = document.getElementById('timeRemaining');
    const startBtn = document.getElementById('startBtn');
    const stopBtn = document.getElementById('stopBtn');

    function startExercise() {
        isRunning = true;
        currentCycle = 0;
        startTime = Date.now();
        startBtn.classList.add('hidden');
        stopBtn.classList.remove('hidden');
        runCycle();
    }

    function stopExercise() {
        isRunning = false;
        if (timeoutId) clearTimeout(timeoutId);
        circle.className = 'breathing-circle';
        text.textContent = 'Arrêté';
        startBtn.classList.remove('hidden');
        startBtn.textContent = 'Recommencer';
        stopBtn.classList.add('hidden');
    }

    function runCycle() {
        if (!isRunning || currentCycle >= CONFIG.totalCycles) {
            finishExercise();
            return;
        }

        currentCycle++;
        cycleCounter.textContent = 'Cycle : ' + currentCycle + ' / ' + CONFIG.totalCycles;

        // Inspiration phase
        startPhase('Inspirez', 'phase-inspire', CONFIG.inspiration, function() {
            // Apnea phase
            if (CONFIG.apnea > 0) {
                startPhase('Retenez', 'phase-hold', CONFIG.apnea, function() {
                    // Expiration phase
                    startPhase('Expirez', 'phase-expire', CONFIG.expiration, function() {
                        runCycle();
                    });
                });
            } else {
                // Expiration phase (no apnea)
                startPhase('Expirez', 'phase-expire', CONFIG.expiration, function() {
                    runCycle();
                });
            }
        });
    }

    function startPhase(label, className, duration, callback) {
        if (!isRunning) return;

        circle.className = 'breathing-circle ' + className;
        let remaining = duration;
        text.textContent = label + ' (' + remaining + ')';

        function countdown() {
            if (!isRunning) return;
            remaining--;
            if (remaining > 0) {
                text.textContent = label + ' (' + remaining + ')';
                timeoutId = setTimeout(countdown, 1000);
            } else {
                callback();
            }
        }

        timeoutId = setTimeout(countdown, 1000);
    }

    function finishExercise() {
        isRunning = false;
        circle.className = 'breathing-circle phase-done';
        text.textContent = 'Terminé !';
        startBtn.classList.remove('hidden');
        startBtn.textContent = 'Recommencer';
        stopBtn.classList.add('hidden');

        var elapsed = Math.round((Date.now() - startTime) / 1000);
        timeRemaining.textContent = 'Durée totale : ' + elapsed + 's';

        // Save session if authenticated
        if (CONFIG.isAuthenticated) {
            fetch(CONFIG.saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CONFIG.csrfToken
                },
                body: JSON.stringify({
                    breathing_exercise_id: CONFIG.exerciseId,
                    inspiration_duration: CONFIG.inspiration,
                    apnea_duration: CONFIG.apnea,
                    expiration_duration: CONFIG.expiration,
                    total_cycles: CONFIG.totalCycles,
                    duration_seconds: elapsed
                })
            });
        }
    }
</script>
@endsection
