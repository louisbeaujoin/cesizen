package com.cesizen.breathing

import android.app.AlertDialog
import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import androidx.appcompat.app.AppCompatActivity
import com.cesizen.breathing.databinding.ActivityAdminListBinding
import com.cesizen.breathing.databinding.ItemAdminExerciseBinding

class AdminExercisesActivity : AppCompatActivity() {

    private lateinit var binding: ActivityAdminListBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityAdminListBinding.inflate(layoutInflater)
        setContentView(binding.root)

        setSupportActionBar(binding.toolbar)
        supportActionBar?.title = getString(R.string.admin_exercises_title)
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        binding.toolbar.setNavigationOnClickListener { finish() }

        binding.btnAdd.setOnClickListener {
            startActivity(Intent(this, AdminExerciseFormActivity::class.java))
        }
    }

    override fun onResume() {
        super.onResume()
        renderList()
    }

    private fun renderList() {
        binding.itemList.removeAllViews()
        val inflater = LayoutInflater.from(this)

        LocalDataManager.getExercises(this).forEach { ex ->
            val item = ItemAdminExerciseBinding.inflate(inflater, binding.itemList, false)
            item.exName.text = ex.name
            item.exTiming.text = "Inspiration: ${ex.inspirationDuration}s  Apnée: ${ex.apneaDuration}s  Expiration: ${ex.expirationDuration}s"
            item.exStatus.text = if (ex.isActive) getString(R.string.ex_active) else getString(R.string.ex_inactive)

            item.btnEdit.setOnClickListener {
                startActivity(Intent(this, AdminExerciseFormActivity::class.java).apply {
                    putExtra(AdminExerciseFormActivity.EXTRA_EXERCISE_ID, ex.id)
                })
            }

            item.btnToggle.setOnClickListener {
                LocalDataManager.toggleExercise(this, ex.id)
                renderList()
            }

            item.btnDelete.setOnClickListener {
                AlertDialog.Builder(this)
                    .setTitle(getString(R.string.confirm_delete_exercise))
                    .setMessage(ex.name)
                    .setPositiveButton("Supprimer") { _, _ ->
                        LocalDataManager.deleteExercise(this, ex.id)
                        renderList()
                    }
                    .setNegativeButton("Annuler", null)
                    .show()
            }

            binding.itemList.addView(item.root)
        }
    }
}
