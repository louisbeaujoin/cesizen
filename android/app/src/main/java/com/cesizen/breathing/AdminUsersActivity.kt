package com.cesizen.breathing

import android.app.AlertDialog
import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import androidx.appcompat.app.AppCompatActivity
import com.cesizen.breathing.databinding.ActivityAdminListBinding
import com.cesizen.breathing.databinding.ItemAdminUserBinding

class AdminUsersActivity : AppCompatActivity() {

    private lateinit var binding: ActivityAdminListBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityAdminListBinding.inflate(layoutInflater)
        setContentView(binding.root)

        setSupportActionBar(binding.toolbar)
        supportActionBar?.title = getString(R.string.admin_users_title)
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        binding.toolbar.setNavigationOnClickListener { finish() }

        binding.btnAdd.setOnClickListener {
            startActivity(Intent(this, AdminUserFormActivity::class.java))
        }
    }

    override fun onResume() {
        super.onResume()
        renderList()
    }

    private fun renderList() {
        binding.itemList.removeAllViews()
        val currentUser = LocalAuthManager.getCurrentUser(this)
        val inflater = LayoutInflater.from(this)

        LocalAuthManager.getAllUsers(this).forEach { user ->
            val item = ItemAdminUserBinding.inflate(inflater, binding.itemList, false)
            item.userName.text = user.name
            item.userEmail.text = user.email
            item.userRole.text = if (user.role == "admin") "Admin" else "User"
            item.userStatus.text = if (user.isActive) getString(R.string.user_active) else getString(R.string.user_inactive)

            item.btnEdit.setOnClickListener {
                startActivity(Intent(this, AdminUserFormActivity::class.java).apply {
                    putExtra(AdminUserFormActivity.EXTRA_USER_ID, user.id)
                })
            }

            item.btnToggle.setOnClickListener {
                val ok = LocalAuthManager.adminToggleUser(this, user.id, currentUser?.id ?: -1)
                if (!ok) showAlert("Impossible de modifier votre propre compte.")
                else renderList()
            }

            item.btnDelete.setOnClickListener {
                AlertDialog.Builder(this)
                    .setTitle(getString(R.string.confirm_delete_user))
                    .setMessage("${user.name} (${user.email})")
                    .setPositiveButton("Supprimer") { _, _ ->
                        val ok = LocalAuthManager.adminDeleteUser(this, user.id, currentUser?.id ?: -1)
                        if (!ok) showAlert("Impossible de supprimer votre propre compte.")
                        else renderList()
                    }
                    .setNegativeButton("Annuler", null)
                    .show()
            }

            binding.itemList.addView(item.root)
        }
    }

    private fun showAlert(msg: String) {
        AlertDialog.Builder(this).setMessage(msg).setPositiveButton("OK", null).show()
    }
}
