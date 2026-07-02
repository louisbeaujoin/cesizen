package com.cesizen.breathing

import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.fragment.app.Fragment
import com.cesizen.breathing.databinding.FragmentBreathingBinding
import com.cesizen.breathing.databinding.ItemExerciseBinding

class BreathingFragment : Fragment() {

    private var _binding: FragmentBreathingBinding? = null
    private val binding get() = _binding!!

    override fun onResume() {
        super.onResume()
        loadExercises()
    }

    override fun onCreateView(inflater: LayoutInflater, container: ViewGroup?, savedInstanceState: Bundle?): View {
        _binding = FragmentBreathingBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        loadExercises()
        setupCustomForm()
    }

    private fun loadExercises() {
        binding.exerciseList.removeAllViews()
        val localExercises = LocalDataManager.getActiveExercises(requireContext())
        renderExercises(localExercises.map { ex ->
            BreathingExercise(
                name = ex.name,
                description = ex.description,
                inspiration = ex.inspirationDuration,
                apnea = ex.apneaDuration,
                expiration = ex.expirationDuration
            )
        })
    }

    private fun renderExercises(exercises: List<BreathingExercise>) {
        if (_binding == null) return
        val inflater = LayoutInflater.from(requireContext())
        exercises.forEach { exercise ->
            val card = ItemExerciseBinding.inflate(inflater, binding.exerciseList, false)
            card.exerciseName.text = exercise.name
            card.exerciseDescription.text = exercise.description
            card.exerciseTiming.text = getString(R.string.exercise_timing, exercise.inspiration, exercise.apnea, exercise.expiration)
            card.exerciseCycle.text = getString(R.string.exercise_cycle_duration, exercise.cycleDuration)
            card.startButton.setOnClickListener { launchExercise(exercise) }
            binding.exerciseList.addView(card.root)
        }
    }

    private fun setupCustomForm() {
        binding.startCustomButton.setOnClickListener {
            val inspiration = binding.inspirationInput.text.toString().toIntOrNull() ?: 5
            val apnea = binding.apneaInput.text.toString().toIntOrNull() ?: 0
            val expiration = binding.expirationInput.text.toString().toIntOrNull() ?: 5
            val cycles = binding.cyclesInput.text.toString().toIntOrNull() ?: 6

            if (inspiration !in 1..30 || apnea !in 0..30 || expiration !in 1..30 || cycles !in 1..30) {
                Toast.makeText(requireContext(), R.string.invalid_values, Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }

            launchExercise(BreathingExercise(
                name = getString(R.string.custom_exercise),
                description = "",
                inspiration = inspiration,
                apnea = apnea,
                expiration = expiration,
                cycles = cycles
            ))
        }
    }

    private fun launchExercise(exercise: BreathingExercise) {
        val intent = Intent(requireContext(), ExerciseActivity::class.java).apply {
            putExtra(ExerciseActivity.EXTRA_NAME, exercise.name)
            putExtra(ExerciseActivity.EXTRA_INSPIRATION, exercise.inspiration)
            putExtra(ExerciseActivity.EXTRA_APNEA, exercise.apnea)
            putExtra(ExerciseActivity.EXTRA_EXPIRATION, exercise.expiration)
            putExtra(ExerciseActivity.EXTRA_CYCLES, exercise.cycles)
        }
        startActivity(intent)
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}
