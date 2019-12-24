package ep.project.androidapp.entities

import java.io.Serializable

data class Item(
    val id: Int = 0,
    val name: String = ""
) : Serializable