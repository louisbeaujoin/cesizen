package com.cesizen.breathing

import com.google.gson.annotations.SerializedName

data class ApiExercise(
    val id: Int,
    val name: String,
    val description: String,
    @SerializedName("inspiration_duration") val inspirationDuration: Int,
    @SerializedName("apnea_duration") val apneaDuration: Int,
    @SerializedName("expiration_duration") val expirationDuration: Int
)

data class ApiInfoPage(
    val id: Int,
    val title: String,
    val slug: String,
    val content: String? = null
)

data class ApiUser(
    val id: Int,
    val name: String,
    val email: String,
    val role: String
)

data class LoginRequest(val email: String, val password: String)
data class RegisterRequest(val name: String, val email: String, val password: String, @SerializedName("password_confirmation") val passwordConfirmation: String)
data class UpdateProfileRequest(val name: String, val email: String)
data class UpdatePasswordRequest(@SerializedName("current_password") val currentPassword: String, val password: String, @SerializedName("password_confirmation") val passwordConfirmation: String)
data class SaveSessionRequest(
    @SerializedName("breathing_exercise_id") val exerciseId: Int?,
    @SerializedName("inspiration_duration") val inspirationDuration: Int,
    @SerializedName("apnea_duration") val apneaDuration: Int,
    @SerializedName("expiration_duration") val expirationDuration: Int,
    @SerializedName("total_cycles") val totalCycles: Int,
    @SerializedName("duration_seconds") val durationSeconds: Int
)

data class AuthResponse(val token: String, val user: ApiUser)
data class ApiError(val error: String? = null, val message: String? = null)
