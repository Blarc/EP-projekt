package ep.project.androidapp.enums

enum class ProfileSpinnerEnum(val value: Int, val text: String) {
    ITEMS(0, "Items"),
    SHOPPING_LISTS(1, "Shopping lists"),
    ARCHIVE(2, "Archive"),
    SETTINGS(3, "Settings"),
    LOG_OUT(4, "Log out");

    override fun toString(): String {
        return this.text
    }
}