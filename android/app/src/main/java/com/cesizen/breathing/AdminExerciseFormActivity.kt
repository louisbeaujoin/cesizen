package com.cesizen.breathing

import android.os.Bundle
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import com.cesizen.breathing.databinding.ActivityAdminExerciseFormBinding

class AdminExerciseFormActivity : AppCompatActivity() {

    private lateinit var binding: ActivityAdminExerciseFormBinding
    private var editId: Int = -1

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityAdminExerciseFormBinding.inflate(layoutInflater)
        setContentView(binding.root)

        editId = intent.getIntExtra(EXTRA_EXERCISE_ID, -1)
        val isEdit = editId != -1

        setSupportActionBar(binding.toolbar)
        supportActionBar?.title = if (isEdit) "Modifier l'exercice" else "Nouvel exercice"
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        binding.toolbar.setNavigationOnClickListener { finish() }

        if (isEdit) {
            val ex = LocalDataManager.getExercises(this).find { it.id == editId }
            if (ex != null) {
                binding.nameInput.setText(ex.name)
                binding.descriptionInput.setText(ex.description)
                binding.inspirationInput.setText(ex.inspirationDuration.toString())
                binding.apneaInput.setText(ex.apneaDuration.toString())
                binding.expirationInput.setText(ex.expirationDuration.toString())
                binding.isActiveCheck.isChecked = ex.isActive
            }
        }

        binding.saveButton.setOnClickListener { save() }
    }

    private fun save() {
        val name = binding.nameInput.text.toString().trim()
        val description = binding.descriptionInput.text.toString().trim()
        val inspiration = binding.inspirationInput.text.toString().toIntOrNull() ?: 0
        val apnea = binding.apneaInput.text.toString().toIntOrNull() ?: 0
        val expiration = binding.expirationInput.text.toString().toIntOrNull() ?: 0
        val isActive = binding.isActiveCheck.isChecked

        if (name.isEmpty()) { showError("Le nom est obligatoire."); return }
        if (inspiration !in 1..30) { showError("L'inspiration doit être entre 1 et 30 secondes."); return }
        if (apnea !in 0..30) { showError("L'apnée doit être entre 0 et 30 secondes."); return }
        if (expiration !in 1..30) { showError("L'expiration doit être entre 1 et 30 secondes."); return }

        if (editId != -1) {
            LocalDataManager.updateExercise(this, editId, name, description, inspiration, apnea, expiration, isActive)
        } else {
            LocalDataManager.addExercise(this, name, description, inspiration, apnea, expiration, isActive)
        }

        finish()
    }

    private fun showError(msg: String) {
        binding.errorText.text = msg
        binding.errorText.visibility = View.VISIBLE
    }

    companion object {
        const val EXTRA_EXERCISE_ID = "extra_exercise_id"
    }
}
