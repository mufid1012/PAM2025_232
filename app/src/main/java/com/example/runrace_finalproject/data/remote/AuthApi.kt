package com.example.runrace_finalproject.data.remote

import com.example.runrace_finalproject.data.model.AuthResponse
import com.example.runrace_finalproject.data.model.LoginRequest
import com.example.runrace_finalproject.data.model.RegisterRequest
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.POST

interface AuthApi {
    
    @POST("auth/login")
    suspend fun login(@Body request: LoginRequest): Response<AuthResponse>
    
    @POST("auth/register")
    suspend fun register(@Body request: RegisterRequest): Response<AuthResponse>
    
    @POST("auth/logout")
    suspend fun logout(): Response<AuthResponse>
    
    @GET("auth/me")
    suspend fun getCurrentUser(): Response<AuthResponse>
}
