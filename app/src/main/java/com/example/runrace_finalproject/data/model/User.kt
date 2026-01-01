package com.example.runrace_finalproject.data.model

import com.google.gson.annotations.SerializedName

data class User(
    @SerializedName("id")
    val id: Int,
    
    @SerializedName("name")
    val name: String,
    
    @SerializedName("email")
    val email: String,
    
    @SerializedName("role")
    val role: String, // "user" or "admin"
    
    @SerializedName("photo_url")
    val photoUrl: String? = null
)
