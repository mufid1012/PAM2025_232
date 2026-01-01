package com.example.runrace_finalproject.data.remote

import com.example.runrace_finalproject.data.model.ApiResponse
import com.example.runrace_finalproject.data.model.News
import com.example.runrace_finalproject.data.model.NewsRequest
import retrofit2.Response
import retrofit2.http.*

interface NewsApi {
    
    @GET("news")
    suspend fun getAllNews(): Response<ApiResponse<List<News>>>
    
    @GET("news/featured")
    suspend fun getFeaturedNews(): Response<ApiResponse<List<News>>>
    
    @GET("news/{id}")
    suspend fun getNewsById(@Path("id") id: Int): Response<ApiResponse<News>>
    
    @POST("news")
    suspend fun createNews(@Body request: NewsRequest): Response<ApiResponse<News>>
    
    @PUT("news/{id}")
    suspend fun updateNews(@Path("id") id: Int, @Body request: NewsRequest): Response<ApiResponse<News>>
    
    @DELETE("news/{id}")
    suspend fun deleteNews(@Path("id") id: Int): Response<ApiResponse<Unit>>
}
