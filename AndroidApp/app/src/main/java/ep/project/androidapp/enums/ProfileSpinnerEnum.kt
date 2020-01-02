package ep.project.androidapp.enums

enum class ProfileSpinnerEnum(var text: String) {
    SHOPPING_LISTS("Shopping lists"),
    SETTINGS("Settings"),
    LOG_OUT("Log out");

    override fun toString(): String {
        return this.text
    }

//  TODO REMOVE THIS
//    companion object {
//        fun stringValues(): List<String> {
//            val list = ArrayList<String>()
//            values().forEach {
//                list.add(it.toString())
//            }
//            return list
//        }
//    }
}