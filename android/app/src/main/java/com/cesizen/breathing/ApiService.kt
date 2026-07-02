package com.cesizen.breathing

import retrofit2.Response
import retrofit2.http.*

interface ApiService {
    @POST("login")
    suspend fun login(@Body request: LoginRequest): Response<AuthResponse>

    @POST("register")
    suspend fun register(@Body request: RegisterRequest): Response<AuthResponse>

    @POST("logout")
    suspend fun logout(@Header("Authorization") token: String): Response<Unit>

    @GET("profile")
    suspend fun getProfile(@Header("Authorization") token: String): Response<ApiUser>

    @PUT("profile")
    suspend fun updateProfile(@Header("Authorization") token: String, @Body request: UpdateProfileRequest): Response<ApiUser>

    @PUT("profile/password")
    suspend fun updatePassword(@Header("Authorization") token: String, @Body request: UpdatePasswordRequest): Response<Unit>

    @GET("exercises")
    suspend fun getExercises(): Response<List<ApiExercise>>

    @GET("information")
    suspend fun getInformation(): Response<List<ApiInfoPage>>

    @GET("information/{slug}")
    suspend fun getInformationDetail(@Path("slug") slug: String): Response<ApiInfoPage>

    @POST("sessions")
    suspend fun saveSession(@Header("Authorization") token: String, @Body request: SaveSessionRequest): Response<Unit>
}
