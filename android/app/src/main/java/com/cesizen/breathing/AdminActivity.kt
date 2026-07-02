package com.cesizen.breathing

import android.content.Intent
import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import com.cesizen.breathing.databinding.ActivityAdminBinding

class AdminActivity : AppCompatActivity() {

    private lateinit var binding: ActivityAdminBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityAdminBinding.inflate(layoutInflater)
        setContentView(binding.root)

        setSupportActionBar(binding.toolbar)
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        binding.toolbar.setNavigationOnClickListener { finish() }

        binding.btnManageUsers.setOnClickListener {
            startActivity(Intent(this, AdminUsersActivity::class.java))
        }
        binding.btnManageExercises.setOnClickListener {
            startActivity(Intent(this, AdminExercisesActivity::class.java))
        }
        binding.btnManagePages.setOnClickListener {
            startActivity(Intent(this, AdminInfoPagesActivity::class.java))
        }
    }

    override fun onResume() {
        super.onResume()
        refreshStats()
    }

    private fun refreshStats() {
        binding.statUsers.text = LocalAuthManager.getUserCount(this).toString()
        binding.statExercises.text = LocalDataManager.getExercises(this).size.toString()
        binding.statPages.text = LocalDataManager.getInfoPages(this).size.toString()
        binding.statSessions.text = "0"
    }
}
