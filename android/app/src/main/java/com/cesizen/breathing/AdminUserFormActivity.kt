package com.cesizen.breathing

import android.os.Bundle
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import com.cesizen.breathing.databinding.ActivityAdminUserFormBinding

class AdminUserFormActivity : AppCompatActivity() {

    private lateinit var binding: ActivityAdminUserFormBinding
    private var editUserId: Int = -1

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityAdminUserFormBinding.inflate(layoutInflater)
        setContentView(binding.root)

        editUserId = intent.getIntExtra(EXTRA_USER_ID, -1)
        val isEdit = editUserId != -1

        setSupportActionBar(binding.toolbar)
        supportActionBar?.title = if (isEdit) "Modifier l'utilisateur" else "Nouvel utilisateur"
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        binding.toolbar.setNavigationOnClickListener { finish() }

        if (isEdit) {
            binding.passwordLayout.hint = "Nouveau mot de passe (laisser vide pour ne pas changer)"
            val user = LocalAuthManager.getAllUsers(this).find { it.id == editUserId }
            if (user != null) {
                binding.nameInput.setText(user.name)
                binding.emailInput.setText(user.email)
                if (user.role == "admin") binding.roleAdmin.isChecked = true
                else binding.roleUser.isChecked = true
            }
        }

        binding.saveButton.setOnClickListener { save() }
    }

    private fun save() {
        val name = binding.nameInput.text.toString().trim()
        val email = binding.emailInput.text.toString().trim()
        val password = binding.passwordInput.text.toString()
        val role = if (binding.roleAdmin.isChecked) "admin" else "user"
        val isEdit = editUserId != -1

        if (name.isEmpty() || email.isEmpty()) {
            showError("Veuillez remplir le nom et l'email.")
            return
        }

        if (!isEdit && password.isEmpty()) {
            showError("Le mot de passe est obligatoire pour un nouvel utilisateur.")
            return
        }

        if (password.isNotEmpty() && password.length < 8) {
            showError("Le mot de passe doit contenir au moins 8 caractères.")
            return
        }

        if (isEdit) {
            val ok = LocalAuthManager.adminUpdateUser(this, editUserId, name, email, role)
            if (!ok) { showError("Cette adresse email est déjà utilisée."); return }
            if (password.isNotEmpty()) {
                LocalAuthManager.adminUpdateUserPassword(this, editUserId, password)
            }
        } else {
            val user = LocalAuthManager.adminAddUser(this, name, email, password, role)
            if (user == null) { showError("Cette adresse email est déjà utilisée."); return }
        }

        finish()
    }

    private fun showError(msg: String) {
        binding.errorText.text = msg
        binding.errorText.visibility = View.VISIBLE
    }

    companion object {
        const val EXTRA_USER_ID = "extra_user_id"
    }
}
