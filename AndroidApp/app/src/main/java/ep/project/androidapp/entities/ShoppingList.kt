package ep.project.androidapp.entities

import java.io.Serializable


data class ShoppingList(
    val id: Int,
    val name: String,
    val items: List<Item>

) : Serializable {
    override fun equals(other: Any?): Boolean {
        if (this === other) return true
        if (javaClass != other?.javaClass) return false

        other as ShoppingList

        if (id != other.id) return false
        if (name != other.name) return false
        if (items != other.items) return false

        return true
    }

    override fun hashCode(): Int {
        var result = id
        result = 31 * result + name.hashCode()
        result = 31 * result + items.hashCode()
        return result
    }
}
