package ep.project.androidapp.entities

import java.io.Serializable

data class Item(
    val id: Int = 0,
    val name: String,
    val price: Float,
    val description: String = "This is a test description." +
            " If you see this, then the item has no description."


) : Serializable {
    override fun equals(other: Any?): Boolean {
        if (this === other) return true
        if (javaClass != other?.javaClass) return false

        other as Item

        if (id != other.id) return false
        if (name != other.name) return false
        if (price != other.price) return false
        if (description != other.description) return false

        return true
    }

    override fun hashCode(): Int {
        var result = id
        result = 31 * result + name.hashCode()
        result = 31 * result + price.hashCode()
        result = 31 * result + description.hashCode()
        return result
    }
}