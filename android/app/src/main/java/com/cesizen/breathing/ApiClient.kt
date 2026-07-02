package com.cesizen.breathing

import okhttp3.OkHttpClient
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory

object ApiClient {
    // 10.0.2.2 est l'adresse du host depuis l'émulateur Android
    private const val BASE_URL = "http://10.0.2.2/CESIZEN/cesizen/public/api/"

    private val client = OkHttpClient.Builder().build()

    val service: ApiService by lazy {
        Retrofit.Builder()
            .baseUrl(BASE_URL)
            .client(client)
            .addConverterFactory(GsonConverterFactory.create())
            .build()
            .create(ApiService::class.java)
    }
}
