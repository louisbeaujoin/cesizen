package com.cesizen.breathing

import android.content.Context
import android.content.SharedPreferences

object AuthManager {
    private const val PREFS_NAME = "cesizen_prefs"
    private const val KEY_TOKEN = "api_token"
    private const val KEY_USER_NAME = "user_name"
    private const val KEY_USER_EMAIL = "user_email"
    private const val KEY_USER_ROLE = "user_role"

    private fun prefs(context: Context): SharedPreferences =
        context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)

    fun saveAuth(context: Context, token: String, user: ApiUser) {
        prefs(context).edit()
            .putString(KEY_TOKEN, token)
            .putString(KEY_USER_NAME, user.name)
            .putString(KEY_USER_EMAIL, user.email)
            .putString(KEY_USER_ROLE, user.role)
            .apply()
    }

    fun clearAuth(context: Context) {
        prefs(context).edit().clear().apply()
    }

    fun getToken(context: Context): String? = prefs(context).getString(KEY_TOKEN, null)

    fun getBearerToken(context: Context): String? = getToken(context)?.let { "Bearer $it" }

    fun isLoggedIn(context: Context): Boolean = getToken(context) != null

    fun getUserName(context: Context): String = prefs(context).getString(KEY_USER_NAME, "") ?: ""

    fun getUserEmail(context: Context): String = prefs(context).getString(KEY_USER_EMAIL, "") ?: ""

    fun updateUser(context: Context, name: String, email: String) {
        prefs(context).edit()
            .putString(KEY_USER_NAME, name)
            .putString(KEY_USER_EMAIL, email)
            .apply()
    }
}
