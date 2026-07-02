package com.cesizen.breathing

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import com.cesizen.breathing.databinding.FragmentHomeBinding

class HomeFragment : Fragment() {

    private var _binding: FragmentHomeBinding? = null
    private val binding get() = _binding!!

    override fun onCreateView(inflater: LayoutInflater, container: ViewGroup?, savedInstanceState: Bundle?): View {
        _binding = FragmentHomeBinding.inflate(inflater, container, false)
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
        val isLoggedIn = LocalAuthManager.isLoggedIn(requireContext())
        binding.cardAccount.visibility = if (isLoggedIn) View.GONE else View.VISIBLE

        binding.btnGoInfo.setOnClickListener {
            (activity as? MainActivity)?.navigateTo(R.id.nav_information)
        }

        binding.btnGoBreathing.setOnClickListener {
            (activity as? MainActivity)?.navigateTo(R.id.nav_breathing)
        }

        binding.btnGoAccount.setOnClickListener {
            (activity as? MainActivity)?.navigateTo(R.id.nav_profile)
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}
