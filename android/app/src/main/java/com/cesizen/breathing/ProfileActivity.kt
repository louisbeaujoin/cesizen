package com.cesizen.breathing

import android.os.Bundle
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import com.cesizen.breathing.databinding.ActivityProfileBinding

class ProfileActivity : AppCompatActivity() {

    private lateinit var binding: ActivityProfileBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityProfileBinding.inflate(layoutInflater)
        setContentView(binding.root)

        setSupportActionBar(binding.toolbar)
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        binding.toolbar.setNavigationOnClickListener { finish() }

        val user = LocalAuthManager.getCurrentUser(this)
        if (user != null) {
            binding.nameInput.setText(user.name)
            binding.emailInput.setText(user.email)
        }

        binding.updateProfileButton.setOnClickListener { updateProfile() }
        binding.changePasswordButton.setOnClickListener { changePassword() }
    }

    private fun updateProfile() {
        val name = binding.nameInput.text.toString().trim()
        val email = binding.emailInput.text.toString().trim()

        if (name.isEmpty() || email.isEmpty()) {
            showError("Veuillez remplir tous les champs.")
            return
        }

        clearMessages()
        val ok = LocalAuthManager.updateProfile(this, name, email)

        if (ok) {
            showSuccess("Profil mis à jour.")
        } else {
            showError("Cette adresse email est déjà utilisée par un autre compte.")
        }
    }

    private fun changePassword() {
        val current = binding.currentPasswordInput.text.toString()
        val newPass = binding.newPasswordInput.text.toString()
        val confirm = binding.confirmNewPasswordInput.text.toString()

        if (current.isEmpty() || newPass.isEmpty() || confirm.isEmpty()) {
            showError("Veuillez remplir tous les champs.")
            return
        }

        if (newPass.length < 8) {
            showError("Le nouveau mot de passe doit contenir au moins 8 caractères.")
            return
        }

        if (newPass != confirm) {
            showError("Les mots de passe ne correspondent pas.")
            return
        }

        clearMessages()
        val ok = LocalAuthManager.updatePassword(this, current, newPass)

        if (ok) {
            binding.currentPasswordInput.text?.clear()
            binding.newPasswordInput.text?.clear()
            binding.confirmNewPasswordInput.text?.clear()
            showSuccess("Mot de passe mis à jour.")
        } else {
            showError("Mot de passe actuel incorrect.")
        }
    }

    private fun showError(msg: String) {
        binding.errorText.text = msg
        binding.errorText.visibility = View.VISIBLE
        binding.successText.visibility = View.GONE
    }

    private fun showSuccess(msg: String) {
        binding.successText.text = msg
        binding.successText.visibility = View.VISIBLE
        binding.errorText.visibility = View.GONE
    }

    private fun clearMessages() {
        binding.errorText.visibility = View.GONE
        binding.successText.visibility = View.GONE
    }
}
