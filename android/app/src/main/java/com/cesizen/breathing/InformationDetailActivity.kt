package com.cesizen.breathing

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import com.cesizen.breathing.databinding.ActivityInformationDetailBinding
import kotlinx.coroutines.launch

class InformationDetailActivity : AppCompatActivity() {

    private lateinit var binding: ActivityInformationDetailBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityInformationDetailBinding.inflate(layoutInflater)
        setContentView(binding.root)

        val slug = intent.getStringExtra(EXTRA_SLUG) ?: run { finish(); return }
        val title = intent.getStringExtra(EXTRA_TITLE) ?: ""
        val fallbackContent = intent.getStringExtra(EXTRA_CONTENT)

        setSupportActionBar(binding.toolbar)
        supportActionBar?.title = getString(R.string.app_name)
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        binding.toolbar.setNavigationOnClickListener { finish() }

        binding.infoDetailTitle.text = title

        // Affichage immédiat du contenu de fallback si disponible
        if (!fallbackContent.isNullOrEmpty()) {
            binding.infoDetailContent.text = fallbackContent
        }

        // Tentative de chargement depuis l'API
        lifecycleScope.launch {
            try {
                val response = ApiClient.service.getInformationDetail(slug)
                if (response.isSuccessful) {
                    val page = response.body()!!
                    binding.infoDetailTitle.text = page.title
                    binding.infoDetailContent.text = page.content ?: fallbackContent ?: ""
                }
            } catch (_: Exception) {
                // Contenu fallback déjà affiché, rien à faire
            }
        }
    }

    companion object {
        const val EXTRA_SLUG = "extra_slug"
        const val EXTRA_TITLE = "extra_title"
        const val EXTRA_CONTENT = "extra_content"
    }
}
