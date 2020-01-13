package ep.project.androidapp.entities

import java.io.Serializable

data class User(
    val id: Int,
    val firstName: String,
    val lastName: String,
    val email: String,
    val telephone: String,
    val role: String,
    val address: Address,
    val password: String,
    val apiToken: String,
    val shoppingLists: List<ShoppingList>
) : Serializable