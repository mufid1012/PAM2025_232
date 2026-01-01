package com.example.runrace_finalproject.data.remote

import com.example.runrace_finalproject.data.model.ApiResponse
import com.example.runrace_finalproject.data.model.ChangePasswordRequest
import com.example.runrace_finalproject.data.model.UpdateProfileRequest
import com.example.runrace_finalproject.data.model.User
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.PUT

interface UserApi {
    
    @GET("users/profile")
    suspend fun getProfile(): Response<ApiResponse<User>>
    
    @PUT("users/profile")
    suspend fun updateProfile(@Body request: UpdateProfileRequest): Response<ApiResponse<User>>
    
    @PUT("users/password")
    suspend fun changePassword(@Body request: ChangePasswordRequest): Response<ApiResponse<Unit>>
}
