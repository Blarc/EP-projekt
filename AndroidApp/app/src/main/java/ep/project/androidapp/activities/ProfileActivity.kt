package ep.project.androidapp.activities

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import ep.project.androidapp.ApplicationObject
import ep.project.androidapp.ProfileSpinner
import ep.project.androidapp.R
import kotlinx.android.synthetic.main.activity_profile.*

class ProfileActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_profile)

        ProfileSpinner(this)

        val appObject = application as ApplicationObject
        if (appObject.loggedIn) {
            idProfile.text = appObject.user!!.id.toString()
            nameProfile.text = appObject.user!!.name
            emailProfile.text = appObject.user!!.email
        }
    }

}
