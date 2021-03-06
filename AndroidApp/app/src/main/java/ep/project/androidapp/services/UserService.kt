package ep.project.androidapp.services

import ep.project.androidapp.entities.User
import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Call
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import retrofit2.http.*

object UserService {

    interface RestApi {

        @FormUrlEncoded
        @POST("register")
        fun register(
            @Field("firstName") firstName: String,
            @Field("lastName") lastName: String,
            @Field("email") email: String,
            @Field("password") password: String,
            @Field("password_confirmation") passwordConfirmation: String,
            @Field("telephone") telephone: String,
            @Field("street") street: String,
            @Field("post") post: String,
            @Field("postCode") postCode: String
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
            @Field("firstName") firstName: String,
            @Field("lastName") lastName: String,
            @Field("email") email: String,
            @Field("street") street: String,
            @Field("post") post: String,
            @Field("postCode") postCode: String,
            @Field("telephone") telephone: String,
            @Field("password") password: String,
            @Field("password_confirmation") passwordConfirmation: String
        ): Call<User>

        @GET("users/current")
        fun getCurrent(
            @Header("Authorization") apiToken: String
        ): Call<User>

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