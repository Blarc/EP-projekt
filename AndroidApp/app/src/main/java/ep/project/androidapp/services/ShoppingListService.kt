package ep.project.androidapp.services

import ep.project.androidapp.entities.Item
import ep.project.androidapp.entities.ShoppingList
import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Call
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import retrofit2.http.*

object ShoppingListService {
    interface RestApi {

        @GET("shoppingLists")
        fun getAll(): Call<List<ShoppingList>>

        @GET("shoppingLists/{id}")
        fun get(@Path("id") id: Int): Call<ShoppingList>

        @Headers("Content-Type: application/json")
        @FormUrlEncoded
        @POST("shoppingLists")
        fun insert(
            @Header("Authorization") apiToken: String,
            @Field("name") name: String
        ): Call<ShoppingList>

        @Headers("Content-Type: application/json")
        @PUT("shoppingLists/{id}")
        fun update(
            @Path("id") id: Int,
            @Body shoppingList: ShoppingList
        ): Call<ShoppingList>

        @Headers("Content-Type: application/json")
        @PUT("shoppingLists/{id}/add")
        fun addItem(
            @Path("id") id: Int,
            @Body item: Item
        ): Call<ShoppingList>

        @Headers("Content-Type: application/json")
        @PUT("shoppingLists/{id}/remove")
        fun removeItem(
            @Path("id") id: Int,
            @Body item: Item
        ): Call<ShoppingList>

        @Headers("Content-Type: application/json")
        @PUT("shoppingLists/{id}/decrease")
        fun decreaseItem(
            @Path("id") id: Int,
            @Body item: Item
        ): Call<ShoppingList>

        @DELETE("shoppingLists/{id}")
        fun delete(@Path("id") id: Int): Call<Void>
    }

    val instance: RestApi by lazy {

        val interceptor = HttpLoggingInterceptor()
        interceptor.level = HttpLoggingInterceptor.Level.BODY
        val okHttpClient = OkHttpClient.Builder().addInterceptor(interceptor).build()

        val retrofit = Retrofit.Builder()
            .baseUrl(ep.project.androidapp.Constants.URL)
            .client(okHttpClient)
            .addConverterFactory(GsonConverterFactory.create())
            .build()

        retrofit.create(RestApi::class.java)
    }
}