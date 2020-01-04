package ep.project.androidapp.enums

enum class ShoppingListStatusEnum(val value: Int, val text: String) {
    UNPROCESSED(0, "Unprocessed"),
    PROCESSED(1, "Processed"),
    CANCELED(2, "Canceled"),
    ACTIVE(3, "Active")

}