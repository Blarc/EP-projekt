package ep.project.androidapp.services

import ep.project.androidapp.entities.Item
import ep.project.androidapp.entities.ShoppingList
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

        @FormUrlEncoded
        @POST("shoppingLists")
        fun insert(
            @Header("Authorization") apiToken: String,
            @Field("name") name: String
        ): Call<ShoppingList>

        @FormUrlEncoded
        @PUT("shoppingLists/{id}")
        fun update(
            @Path("id") id: Int,
            @Field("name") name: String
        ): Call<ShoppingList>

        @FormUrlEncoded
        @PUT("shoppingLists/{id}/addItem")
        fun addItems(
            @Path("id") id: Int,
            @Field("item") item: Item
        ): Call<ShoppingList>

        @PUT("shoppingLists/{id}/removeItem")
        @FormUrlEncoded
        fun removeItems(
            @Path("id") id: Int,
            @Field("item") item: Item
        ): Call<ShoppingList>

        @DELETE("shoppingLists/{id}")
        fun delete(@Path("id") id: Int): Call<Void>
    }

    val instance: RestApi by lazy {

        val retrofit = Retrofit.Builder()
            .baseUrl(ep.project.androidapp.Constants.URL)
            .addConverterFactory(GsonConverterFactory.create())
            .build()

        retrofit.create(RestApi::class.java)
    }
}