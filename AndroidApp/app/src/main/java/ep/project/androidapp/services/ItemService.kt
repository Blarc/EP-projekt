package ep.project.androidapp.services

import ep.project.androidapp.Constants
import ep.project.androidapp.entities.Item
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
            @Field("name") name: String
        ): Call<Void>

        @FormUrlEncoded
        @PUT("items/{id}")
        fun update(
            @Path("id") id: Int,
            @Field("name") name: String
        ): Call<Void>

        @DELETE("items/{id}")
        fun delete(@Path("id") id: Int): Call<Void>

    }

    val instance: RestApi by lazy {
        val retrofit = Retrofit.Builder()
            .baseUrl(Constants.URL)
            .addConverterFactory(GsonConverterFactory.create())
            .build()

        retrofit.create(RestApi::class.java)
    }
}