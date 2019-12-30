package ep.project.androidapp

import android.app.Application
import ep.project.androidapp.entities.User

class ApplicationObject : Application() {
    var user: User? = null
    var loggedIn = user == null
}