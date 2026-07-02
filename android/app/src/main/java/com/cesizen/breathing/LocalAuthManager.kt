package com.cesizen.breathing

import android.content.Context
import android.content.SharedPreferences
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.security.MessageDigest

data class LocalUser(
    val id: Int,
    val name: String,
    val email: String,
    val passwordHash: String,
    val role: String = "user",
    val isActive: Boolean = true
)

object LocalAuthManager {

    private const val PREFS_NAME = "cesizen_local_auth"
    private const val KEY_USERS = "users"
    private const val KEY_LOGGED_IN_ID = "logged_in_id"
    private const val KEY_NEXT_ID = "next_id"

    private val gson = Gson()

    private fun prefs(context: Context): SharedPreferences =
        context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)

    private fun hash(password: String): String {
        val bytes = MessageDigest.getInstance("SHA-256").digest(password.toByteArray())
        return bytes.joinToString("") { "%02x".format(it) }
    }

    private fun getUsers(context: Context): MutableList<LocalUser> {
        val json = prefs(context).getString(KEY_USERS, null)
        if (json != null) {
            val type = object : TypeToken<MutableList<LocalUser>>() {}.type
            return gson.fromJson(json, type)
        }
        // Initialisation : création des comptes par défaut
        val defaults = mutableListOf(
            LocalUser(1, "Administrateur", "admin@cesizen.fr", hash("Azerty45"), "admin", true),
            LocalUser(2, "Utilisateur Test", "user@cesizen.fr", hash("Azerty45"), "user", true)
        )
        saveUsers(context, defaults)
        prefs(context).edit().putInt(KEY_NEXT_ID, 3).apply()
        return defaults
    }

    private fun saveUsers(context: Context, users: List<LocalUser>) {
        prefs(context).edit().putString(KEY_USERS, gson.toJson(users)).apply()
    }

    fun login(context: Context, email: String, password: String): LocalUser? {
        val users = getUsers(context)
        val user = users.find { it.email.equals(email.trim(), ignoreCase = true) }
            ?: return null
        if (!user.isActive) return null
        if (user.passwordHash != hash(password)) return null
        prefs(context).edit().putInt(KEY_LOGGED_IN_ID, user.id).apply()
        return user
    }

    fun register(context: Context, name: String, email: String, password: String): LocalUser? {
        val users = getUsers(context)
        if (users.any { it.email.equals(email.trim(), ignoreCase = true) }) return null
        val nextId = prefs(context).getInt(KEY_NEXT_ID, users.size + 1)
        val newUser = LocalUser(nextId, name.trim(), email.trim().lowercase(), hash(password))
        users.add(newUser)
        saveUsers(context, users)
        prefs(context).edit().putInt(KEY_NEXT_ID, nextId + 1).apply()
        prefs(context).edit().putInt(KEY_LOGGED_IN_ID, newUser.id).apply()
        return newUser
    }

    fun getCurrentUser(context: Context): LocalUser? {
        val id = prefs(context).getInt(KEY_LOGGED_IN_ID, -1)
        if (id == -1) return null
        return getUsers(context).find { it.id == id }
    }

    fun isLoggedIn(context: Context): Boolean = getCurrentUser(context) != null

    fun logout(context: Context) {
        prefs(context).edit().remove(KEY_LOGGED_IN_ID).apply()
    }

    fun updateProfile(context: Context, name: String, email: String): Boolean {
        val current = getCurrentUser(context) ?: return false
        val users = getUsers(context)
        val emailLower = email.trim().lowercase()
        if (users.any { it.id != current.id && it.email == emailLower }) return false
        val idx = users.indexOfFirst { it.id == current.id }
        if (idx == -1) return false
        users[idx] = users[idx].copy(name = name.trim(), email = emailLower)
        saveUsers(context, users)
        return true
    }

    fun getAllUsers(context: Context): List<LocalUser> = getUsers(context)

    fun getUserCount(context: Context): Int = getUsers(context).size

    fun adminAddUser(context: Context, name: String, email: String, password: String, role: String): LocalUser? {
        val users = getUsers(context)
        if (users.any { it.email.equals(email.trim(), ignoreCase = true) }) return null
        val nextId = prefs(context).getInt(KEY_NEXT_ID, users.size + 1)
        val user = LocalUser(nextId, name.trim(), email.trim().lowercase(), hash(password), role)
        users.add(user)
        saveUsers(context, users)
        prefs(context).edit().putInt(KEY_NEXT_ID, nextId + 1).apply()
        return user
    }

    fun adminUpdateUser(context: Context, id: Int, name: String, email: String, role: String): Boolean {
        val users = getUsers(context)
        val emailLower = email.trim().lowercase()
        if (users.any { it.id != id && it.email == emailLower }) return false
        val idx = users.indexOfFirst { it.id == id }
        if (idx == -1) return false
        users[idx] = users[idx].copy(name = name.trim(), email = emailLower, role = role)
        saveUsers(context, users)
        return true
    }

    fun adminToggleUser(context: Context, id: Int, currentAdminId: Int): Boolean {
        if (id == currentAdminId) return false
        val users = getUsers(context)
        val idx = users.indexOfFirst { it.id == id }
        if (idx == -1) return false
        users[idx] = users[idx].copy(isActive = !users[idx].isActive)
        saveUsers(context, users)
        return true
    }

    fun adminUpdateUserPassword(context: Context, id: Int, newPassword: String) {
        val users = getUsers(context)
        val idx = users.indexOfFirst { it.id == id }
        if (idx != -1) {
            users[idx] = users[idx].copy(passwordHash = hash(newPassword))
            saveUsers(context, users)
        }
    }

    fun adminDeleteUser(context: Context, id: Int, currentAdminId: Int): Boolean {
        if (id == currentAdminId) return false
        val users = getUsers(context)
        val removed = users.removeAll { it.id == id }
        if (removed) saveUsers(context, users)
        return removed
    }

    fun updatePassword(context: Context, currentPassword: String, newPassword: String): Boolean {
        val current = getCurrentUser(context) ?: return false
        if (current.passwordHash != hash(currentPassword)) return false
        val users = getUsers(context)
        val idx = users.indexOfFirst { it.id == current.id }
        if (idx == -1) return false
        users[idx] = users[idx].copy(passwordHash = hash(newPassword))
        saveUsers(context, users)
        return true
    }
}
