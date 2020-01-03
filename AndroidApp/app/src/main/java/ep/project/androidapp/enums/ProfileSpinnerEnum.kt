package ep.project.androidapp.enums

enum class ProfileSpinnerEnum(var text: String) {
    ITEMS("Items"),
    SHOPPING_LISTS("Shopping lists"),
    SETTINGS("Settings"),
    LOG_OUT("Log out");

    override fun toString(): String {
        return this.text
    }
}