package com.example.runrace_finalproject.data.model

import com.google.gson.annotations.SerializedName

data class Registration(
    @SerializedName("id")
    val id: Int,
    
    @SerializedName("user_id")
    val userId: Int,
    
    @SerializedName("event_id")
    val eventId: Int,
    
    @SerializedName("registered_at")
    val registeredAt: String,
    
    @SerializedName("event")
    val event: Event? = null
)
