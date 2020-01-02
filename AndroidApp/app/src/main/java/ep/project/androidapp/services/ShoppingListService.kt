package ep.project.androidapp.services

import ep.project.androidapp.entities.Item
import ep.project.androidapp.entities.ShoppingList
import retrofit2.Call
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
            @Field("name") name: String,
            @Field("user_id") userId: Int
        ): Call<ShoppingList>

        @FormUrlEncoded
        @PUT("shoppingLists/{id}")
        fun update(
            @Path("id") id: Int,
            @Field("name") name: String
        ): Call<ShoppingList>

        @FormUrlEncoded
        @PUT("shoppingLists/{id}/addItems")
        fun addItems(
            @Path("id") id: Int,
            @Field("items") items: List<Item>
        ): Call<ShoppingList>

        @DELETE("shoppingLists/{id}")
        fun delete(@Path("id") id: Int): Call<Void>
    }
}