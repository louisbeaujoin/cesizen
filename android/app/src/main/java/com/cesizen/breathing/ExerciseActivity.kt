package com.cesizen.breathing

import android.animation.ValueAnimator
import android.content.Context
import android.os.Build
import android.os.Bundle
import android.os.CountDownTimer
import android.os.VibrationEffect
import android.os.Vibrator
import android.os.VibratorManager
import android.view.View
import android.view.animation.AccelerateDecelerateInterpolator
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.lifecycle.lifecycleScope
import com.cesizen.breathing.databinding.ActivityExerciseBinding
import kotlinx.coroutines.launch

class ExerciseActivity : AppCompatActivity() {

    private lateinit var binding: ActivityExerciseBinding
    private lateinit var exercise: BreathingExercise
    private var vibrator: Vibrator? = null

    private var currentCycle = 0
    private var isRunning = false
    private var startTimeMs = 0L
    private var phaseTimer: CountDownTimer? = null
    private var scaleAnimator: ValueAnimator? = null

    private enum class Phase { INSPIRE, HOLD, EXPIRE }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityExerciseBinding.inflate(layoutInflater)
        setContentView(binding.root)

        vibrator = if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.S) {
            (getSystemService(Context.VIBRATOR_MANAGER_SERVICE) as VibratorManager).defaultVibrator
        } else {
            @Suppress("DEPRECATION")
            getSystemService(Context.VIBRATOR_SERVICE) as Vibrator
        }

        exercise = BreathingExercise(
            name = intent.getStringExtra(EXTRA_NAME) ?: getString(R.string.custom_exercise),
            description = "",
            inspiration = intent.getIntExtra(EXTRA_INSPIRATION, 5),
            apnea = intent.getIntExtra(EXTRA_APNEA, 0),
            expiration = intent.getIntExtra(EXTRA_EXPIRATION, 5),
            cycles = intent.getIntExtra(EXTRA_CYCLES, 6)
        )

        setSupportActionBar(binding.toolbar)
        supportActionBar?.title = getString(R.string.app_name)
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        binding.toolbar.setNavigationOnClickListener { finish() }

        binding.exerciseTitle.text = exercise.name
        binding.exerciseTiming.text = getString(
            R.string.exercise_timing_full,
            exercise.inspiration,
            exercise.apnea,
            exercise.expiration,
            exercise.cycles
        )
        binding.cycleCounter.text = getString(R.string.cycle_counter, 0, exercise.cycles)
        binding.breathingText.text = getString(R.string.ready)

        binding.startButton.setOnClickListener { startExercise() }
        binding.stopButton.setOnClickListener { stopExercise() }
    }

    override fun onDestroy() {
        super.onDestroy()
        phaseTimer?.cancel()
        scaleAnimator?.cancel()
        vibrator?.cancel()
    }

    private fun startExercise() {
        isRunning = true
        currentCycle = 0
        startTimeMs = System.currentTimeMillis()
        binding.startButton.visibility = View.GONE
        binding.stopButton.visibility = View.VISIBLE
        binding.totalDuration.text = ""
        runCycle()
    }

    private fun stopExercise() {
        isRunning = false
        phaseTimer?.cancel()
        scaleAnimator?.cancel()
        vibrator?.cancel()
        binding.breathingText.text = getString(R.string.stopped)
        binding.breathingCircle.scaleX = 1f
        binding.breathingCircle.scaleY = 1f
        binding.breathingCircle.background = ContextCompat.getDrawable(this, R.drawable.circle_idle)
        binding.startButton.text = getString(R.string.restart)
        binding.startButton.visibility = View.VISIBLE
        binding.stopButton.visibility = View.GONE
    }

    private fun runCycle() {
        if (!isRunning) return
        if (currentCycle >= exercise.cycles) {
            finishExercise()
            return
        }
        currentCycle++
        binding.cycleCounter.text = getString(R.string.cycle_counter, currentCycle, exercise.cycles)

        startPhase(Phase.INSPIRE) {
            if (exercise.apnea > 0) {
                startPhase(Phase.HOLD) {
                    startPhase(Phase.EXPIRE) { runCycle() }
                }
            } else {
                startPhase(Phase.EXPIRE) { runCycle() }
            }
        }
    }

    private fun startPhase(phase: Phase, onComplete: () -> Unit) {
        if (!isRunning) return

        val (label, drawable, duration) = when (phase) {
            Phase.INSPIRE -> Triple(getString(R.string.inspire), R.drawable.circle_inspire, exercise.inspiration)
            Phase.HOLD -> Triple(getString(R.string.hold), R.drawable.circle_hold, exercise.apnea)
            Phase.EXPIRE -> Triple(getString(R.string.expire), R.drawable.circle_expire, exercise.expiration)
        }

        binding.breathingCircle.background = ContextCompat.getDrawable(this, drawable)
        binding.breathingText.text = "$label ($duration)"

        vibrateForPhase(phase)
        animateCircle(phase, duration)

        phaseTimer?.cancel()
        phaseTimer = object : CountDownTimer(duration * 1000L, 1000L) {
            override fun onTick(millisUntilFinished: Long) {
                val secondsLeft = (millisUntilFinished / 1000).toInt() + 1
                if (isRunning) {
                    binding.breathingText.text = "$label ($secondsLeft)"
                }
            }

            override fun onFinish() {
                if (isRunning) onComplete()
            }
        }.start()
    }

    private fun animateCircle(phase: Phase, durationSec: Int) {
        scaleAnimator?.cancel()
        val (from, to) = when (phase) {
            Phase.INSPIRE -> 1f to 1.3f
            Phase.HOLD -> binding.breathingCircle.scaleX to binding.breathingCircle.scaleX
            Phase.EXPIRE -> binding.breathingCircle.scaleX to 1f
        }
        scaleAnimator = ValueAnimator.ofFloat(from, to).apply {
            duration = durationSec * 1000L
            interpolator = AccelerateDecelerateInterpolator()
            addUpdateListener {
                val v = it.animatedValue as Float
                binding.breathingCircle.scaleX = v
                binding.breathingCircle.scaleY = v
            }
            start()
        }
    }

    private fun vibrateForPhase(phase: Phase) {
        val v = vibrator ?: return
        if (!v.hasVibrator()) return

        val pattern = when (phase) {
            Phase.INSPIRE -> longArrayOf(0, 120)
            Phase.HOLD -> longArrayOf(0, 80, 100, 80)
            Phase.EXPIRE -> longArrayOf(0, 250)
        }
        v.vibrate(VibrationEffect.createWaveform(pattern, -1))
    }

    private fun vibrateFinish() {
        val v = vibrator ?: return
        if (!v.hasVibrator()) return
        v.vibrate(VibrationEffect.createWaveform(longArrayOf(0, 200, 100, 200, 100, 400), -1))
    }

    private fun finishExercise() {
        isRunning = false
        binding.breathingCircle.background = ContextCompat.getDrawable(this, R.drawable.circle_done)
        binding.breathingCircle.scaleX = 1f
        binding.breathingCircle.scaleY = 1f
        binding.breathingText.text = getString(R.string.finished)
        binding.startButton.text = getString(R.string.restart)
        binding.startButton.visibility = View.VISIBLE
        binding.stopButton.visibility = View.GONE

        vibrateFinish()

        val elapsed = ((System.currentTimeMillis() - startTimeMs) / 1000).toInt()
        binding.totalDuration.text = getString(R.string.total_duration, elapsed)

        saveSessionIfLoggedIn(elapsed)
    }

    private fun saveSessionIfLoggedIn(durationSeconds: Int) {
        // Tentative silencieuse de sauvegarde en ligne si connecté
        if (!LocalAuthManager.isLoggedIn(this)) return
        lifecycleScope.launch {
            try {
                val token = AuthManager.getBearerToken(this@ExerciseActivity) ?: return@launch
                ApiClient.service.saveSession(
                    token,
                    SaveSessionRequest(
                        exerciseId = null,
                        inspirationDuration = exercise.inspiration,
                        apneaDuration = exercise.apnea,
                        expirationDuration = exercise.expiration,
                        totalCycles = exercise.cycles,
                        durationSeconds = durationSeconds
                    )
                )
            } catch (_: Exception) {}
        }
    }

    companion object {
        const val EXTRA_NAME = "extra_name"
        const val EXTRA_INSPIRATION = "extra_inspiration"
        const val EXTRA_APNEA = "extra_apnea"
        const val EXTRA_EXPIRATION = "extra_expiration"
        const val EXTRA_CYCLES = "extra_cycles"
    }
}
