package com.example.runrace_finalproject.data.model

import com.google.gson.annotations.SerializedName

data class ApiResponse<T>(
    @SerializedName("success")
    val success: Boolean,
    
    @SerializedName("message")
    val message: String,
    
    @SerializedName("data")
    val data: T? = null
)

data class EventRequest(
    @SerializedName("nama_event")
    val name: String,
    
    @SerializedName("lokasi")
    val location: String,
    
    @SerializedName("kategori")
    val category: String,
    
    @SerializedName("tanggal")
    val date: String,
    
    @SerializedName("status")
    val status: String,
    
    @SerializedName("banner_url")
    val bannerUrl: String? = null
)

data class UpdateProfileRequest(
    @SerializedName("name")
    val name: String,
    
    @SerializedName("photo_url")
    val photoUrl: String? = null
)

data class ChangePasswordRequest(
    @SerializedName("current_password")
    val currentPassword: String,
    
    @SerializedName("new_password")
    val newPassword: String
)

data class NewsRequest(
    @SerializedName("title")
    val title: String,
    
    @SerializedName("content")
    val content: String,
    
    @SerializedName("image_url")
    val imageUrl: String? = null
)
