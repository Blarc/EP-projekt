package ep.project.androidapp.entities

import java.io.Serializable

data class User(
    val id: Int,
    val name: String,
    val email: String,
    val password: String,
    val apiToken: String,
    val shoppingLists: List<ShoppingList>
) : Serializable