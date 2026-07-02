package com.cesizen.breathing

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import androidx.fragment.app.Fragment
import com.cesizen.breathing.databinding.ActivityMainBinding

class MainActivity : AppCompatActivity() {

    private lateinit var binding: ActivityMainBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)
        setSupportActionBar(binding.toolbar)
        supportActionBar?.setDisplayShowTitleEnabled(true)

        if (savedInstanceState == null) {
            showFragment(HomeFragment(), "home")
            binding.bottomNav.selectedItemId = R.id.nav_home
        }

        binding.bottomNav.setOnItemSelectedListener { item ->
            when (item.itemId) {
                R.id.nav_home -> { showFragment(HomeFragment(), "home"); true }
                R.id.nav_breathing -> { showFragment(BreathingFragment(), "breathing"); true }
                R.id.nav_information -> { showFragment(InformationListFragment(), "information"); true }
                R.id.nav_profile -> { showFragment(ProfileFragment(), "profile"); true }
                else -> false
            }
        }
    }

    private fun showFragment(fragment: Fragment, tag: String) {
        supportFragmentManager.beginTransaction()
            .replace(R.id.fragmentContainer, fragment, tag)
            .commit()
    }

    fun navigateTo(itemId: Int) {
        binding.bottomNav.selectedItemId = itemId
    }
}
