package ep.project.androidapp.services

import ep.project.androidapp.entities.User
import retrofit2.Call
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import retrofit2.http.*

object UserService {

    interface RestApi {

        @FormUrlEncoded
        @POST("register")
        fun register(
            @Field("name") name: String,
            @Field("email") email: String,
            @Field("password") password: String,
            @Field("password_confirmation") passwordConfirmation: String
        ): Call<User>

        @FormUrlEncoded
        @POST("login")
        fun login(
            @Field("email") email: String,
            @Field("password") password: String
        ): Call<User>

        @FormUrlEncoded
        @PUT("users/{id}")
        fun update(
            @Path("id") id: Int,
            @Field("name") name: String,
            @Field("email") email: String,
            @Field("password") password: String
        ): Call<User>

    }

    val instance: RestApi by lazy {

        val retrofit = Retrofit.Builder()
            .baseUrl(ep.project.androidapp.Constants.URL)
            .addConverterFactory(GsonConverterFactory.create())
            .build()

        retrofit.create(RestApi::class.java)
    }
}