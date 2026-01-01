package com.example.runrace_finalproject.data.model

import com.google.gson.annotations.SerializedName

data class News(
    @SerializedName("id")
    val id: Int,
    
    @SerializedName("title")
    val title: String,
    
    @SerializedName("content")
    val content: String,
    
    @SerializedName("image_url")
    val imageUrl: String? = null,
    
    @SerializedName("created_at")
    val createdAt: String
)
