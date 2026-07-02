package com.cesizen.breathing

import android.os.Bundle
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import com.cesizen.breathing.databinding.ActivityAdminPageFormBinding

class AdminPageFormActivity : AppCompatActivity() {

    private lateinit var binding: ActivityAdminPageFormBinding
    private var editId: Int = -1

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityAdminPageFormBinding.inflate(layoutInflater)
        setContentView(binding.root)

        editId = intent.getIntExtra(EXTRA_PAGE_ID, -1)
        val isEdit = editId != -1

        setSupportActionBar(binding.toolbar)
        supportActionBar?.title = if (isEdit) "Modifier la page" else "Nouvelle page"
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        binding.toolbar.setNavigationOnClickListener { finish() }

        if (isEdit) {
            val page = LocalDataManager.getInfoPages(this).find { it.id == editId }
            if (page != null) {
                binding.titleInput.setText(page.title)
                binding.contentInput.setText(page.content)
                binding.sortOrderInput.setText(page.sortOrder.toString())
                binding.isPublishedCheck.isChecked = page.isPublished
            }
        }

        binding.saveButton.setOnClickListener { save() }
    }

    private fun save() {
        val title = binding.titleInput.text.toString().trim()
        val content = binding.contentInput.text.toString().trim()
        val sortOrder = binding.sortOrderInput.text.toString().toIntOrNull() ?: 0
        val isPublished = binding.isPublishedCheck.isChecked

        if (title.isEmpty()) { showError("Le titre est obligatoire."); return }
        if (content.isEmpty()) { showError("Le contenu est obligatoire."); return }

        if (editId != -1) {
            LocalDataManager.updateInfoPage(this, editId, title, content, sortOrder, isPublished)
        } else {
            LocalDataManager.addInfoPage(this, title, content, sortOrder, isPublished)
        }

        finish()
    }

    private fun showError(msg: String) {
        binding.errorText.text = msg
        binding.errorText.visibility = View.VISIBLE
    }

    companion object {
        const val EXTRA_PAGE_ID = "extra_page_id"
    }
}
