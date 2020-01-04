package ep.project.androidapp.services

import ep.project.androidapp.Constants
import ep.project.androidapp.entities.Item
import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Call
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import retrofit2.http.*

object ItemService {
    interface RestApi {

        @GET("items")
        fun getAll(): Call<List<Item>>

        @GET("items/{id}")
        fun get(@Path("id") id: Int): Call<Item>

        @FormUrlEncoded
        @POST("items")
        fun insert(
            @Field("name") name: String,
            @Field("description") description: String,
            @Field("price") price: Float
        ): Call<Item>

        @FormUrlEncoded
        @PUT("items/{id}")
        fun update(
            @Path("id") id: Int,
            @Field("name") name: String,
            @Field("description") description: String,
            @Field("price") price: Float
        ): Call<Item>

        @DELETE("items/{id}")
        fun delete(@Path("id") id: Int): Call<Void>

    }

    val instance: RestApi by lazy {
        val interceptor = HttpLoggingInterceptor()
        interceptor.level = HttpLoggingInterceptor.Level.BODY
        val okHttpClient = OkHttpClient.Builder().addInterceptor(interceptor).build()

        val retrofit = Retrofit.Builder()
            .baseUrl(Constants.URL)
            .client(okHttpClient)
            .addConverterFactory(GsonConverterFactory.create())
            .build()

        retrofit.create(RestApi::class.java)
    }
}