package ep.project.androidapp.entities

import java.io.Serializable


data class ShoppingList(
    val id: Int,
    val name: String,
    val items: List<Item>

) : Serializable
