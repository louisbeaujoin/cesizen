package com.cesizen.breathing

import android.app.AlertDialog
import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import androidx.appcompat.app.AppCompatActivity
import com.cesizen.breathing.databinding.ActivityAdminListBinding
import com.cesizen.breathing.databinding.ItemAdminPageBinding

class AdminInfoPagesActivity : AppCompatActivity() {

    private lateinit var binding: ActivityAdminListBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityAdminListBinding.inflate(layoutInflater)
        setContentView(binding.root)

        setSupportActionBar(binding.toolbar)
        supportActionBar?.title = getString(R.string.admin_pages_title)
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        binding.toolbar.setNavigationOnClickListener { finish() }

        binding.btnAdd.setOnClickListener {
            startActivity(Intent(this, AdminPageFormActivity::class.java))
        }
    }

    override fun onResume() {
        super.onResume()
        renderList()
    }

    private fun renderList() {
        binding.itemList.removeAllViews()
        val inflater = LayoutInflater.from(this)

        LocalDataManager.getInfoPages(this).sortedBy { it.sortOrder }.forEach { page ->
            val item = ItemAdminPageBinding.inflate(inflater, binding.itemList, false)
            item.pageTitle.text = page.title
            item.pageStatus.text = if (page.isPublished) getString(R.string.page_published) else getString(R.string.page_draft)
            item.pageOrder.text = "Ordre : ${page.sortOrder}"

            item.btnEdit.setOnClickListener {
                startActivity(Intent(this, AdminPageFormActivity::class.java).apply {
                    putExtra(AdminPageFormActivity.EXTRA_PAGE_ID, page.id)
                })
            }

            item.btnToggle.setOnClickListener {
                LocalDataManager.toggleInfoPage(this, page.id)
                renderList()
            }

            item.btnDelete.setOnClickListener {
                AlertDialog.Builder(this)
                    .setTitle(getString(R.string.confirm_delete_page))
                    .setMessage(page.title)
                    .setPositiveButton("Supprimer") { _, _ ->
                        LocalDataManager.deleteInfoPage(this, page.id)
                        renderList()
                    }
                    .setNegativeButton("Annuler", null)
                    .show()
            }

            binding.itemList.addView(item.root)
        }
    }
}
