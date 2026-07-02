package com.cesizen.breathing

import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import com.cesizen.breathing.databinding.FragmentProfileBinding

class ProfileFragment : Fragment() {

    private var _binding: FragmentProfileBinding? = null
    private val binding get() = _binding!!

    override fun onCreateView(inflater: LayoutInflater, container: ViewGroup?, savedInstanceState: Bundle?): View {
        _binding = FragmentProfileBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        updateView()
    }

    override fun onResume() {
        super.onResume()
        updateView()
    }

    private fun updateView() {
        val ctx = requireContext()
        val user = LocalAuthManager.getCurrentUser(ctx)

        if (user != null) {
            binding.guestView.visibility = View.GONE
            binding.userView.visibility = View.VISIBLE
            binding.profileName.text = user.name
            binding.profileEmail.text = user.email

            val isAdmin = user.role == "admin"
            binding.btnAdmin.visibility = if (isAdmin) View.VISIBLE else View.GONE

            binding.btnEditProfile.setOnClickListener {
                startActivity(Intent(ctx, ProfileActivity::class.java))
            }

            binding.btnLogout.setOnClickListener {
                LocalAuthManager.logout(ctx)
                updateView()
            }

            binding.btnAdmin.setOnClickListener {
                startActivity(Intent(ctx, AdminActivity::class.java))
            }
        } else {
            binding.guestView.visibility = View.VISIBLE
            binding.userView.visibility = View.GONE

            binding.btnLogin.setOnClickListener {
                startActivity(Intent(ctx, LoginActivity::class.java))
            }

            binding.btnRegister.setOnClickListener {
                startActivity(Intent(ctx, RegisterActivity::class.java))
            }
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}
