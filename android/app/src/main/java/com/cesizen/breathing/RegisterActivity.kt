package com.cesizen.breathing

import android.content.Intent
import android.os.Bundle
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import com.cesizen.breathing.databinding.ActivityRegisterBinding

class RegisterActivity : AppCompatActivity() {

    private lateinit var binding: ActivityRegisterBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityRegisterBinding.inflate(layoutInflater)
        setContentView(binding.root)

        setSupportActionBar(binding.toolbar)
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        binding.toolbar.setNavigationOnClickListener { finish() }

        binding.registerButton.setOnClickListener { attemptRegister() }

        binding.loginLink.setOnClickListener {
            startActivity(Intent(this, LoginActivity::class.java))
            finish()
        }
    }

    private fun attemptRegister() {
        val name = binding.nameInput.text.toString().trim()
        val email = binding.emailInput.text.toString().trim()
        val password = binding.passwordInput.text.toString()
        val confirm = binding.passwordConfirmInput.text.toString()

        if (name.isEmpty() || email.isEmpty() || password.isEmpty()) {
            showError("Veuillez remplir tous les champs.")
            return
        }

        if (password.length < 8) {
            showError("Le mot de passe doit contenir au moins 8 caractères.")
            return
        }

        if (password != confirm) {
            showError("Les mots de passe ne correspondent pas.")
            return
        }

        binding.registerButton.isEnabled = false
        binding.errorText.visibility = View.GONE

        val user = LocalAuthManager.register(this, name, email, password)
        binding.registerButton.isEnabled = true

        if (user != null) {
            setResult(RESULT_OK)
            finish()
        } else {
            showError("Cette adresse email est déjà utilisée.")
        }
    }

    private fun showError(msg: String) {
        binding.errorText.text = msg
        binding.errorText.visibility = View.VISIBLE
    }
}
