package ep.project.androidapp.activities

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import ep.project.androidapp.ApplicationObject
import ep.project.androidapp.ProfileSpinner
import ep.project.androidapp.R
import ep.project.androidapp.entities.User
import ep.project.androidapp.services.UserService
import kotlinx.android.synthetic.main.activity_profile_settings.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class ProfileSettingsActivity : AppCompatActivity(), Callback<User> {

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_profile_settings)

        ProfileSpinner(this)

        val user = (application as ApplicationObject).user!!

        nameSettingsProfile.setText(user.name)
        emailSettingsProfile.setText(user.email)

        saveButtonSettingsProfile.setOnClickListener {

            loadingSettingsProfile.visibility = View.VISIBLE
            saveButtonSettingsProfile.visibility = View.GONE

            val password = passwordSettingsProfile.text.trim().toString()
            val passwordConfirm = passwordConfirmSettingsProfile.text.trim().toString()

            if (password == passwordConfirm) {
                // TODO implement user API on server
                UserService.instance.update(
                    user.id,
                    nameSettingsProfile.text.trim().toString(),
                    emailSettingsProfile.text.trim().toString(),
                    password
                ).enqueue(this)
            } else {
                loadingSettingsProfile.visibility = View.GONE
                saveButtonSettingsProfile.visibility = View.VISIBLE
                Toast.makeText(this, "Password doesn't match!", Toast.LENGTH_LONG).show()
            }
        }
    }

    override fun onResponse(call: Call<User>, response: Response<User>) {
        Toast.makeText(this, "Success!", Toast.LENGTH_LONG).show()

        loadingSettingsProfile.visibility = View.GONE
        saveButtonSettingsProfile.visibility = View.VISIBLE

        (application as ApplicationObject).user = response.body()

        val intent = Intent(this, ProfileActivity::class.java)
        startActivity(intent);
    }

    override fun onFailure(call: Call<User>, t: Throwable) {
        Toast.makeText(this, "Error: ${t.message}!", Toast.LENGTH_LONG).show()

        loadingSettingsProfile.visibility = View.GONE
        saveButtonSettingsProfile.visibility = View.VISIBLE
    }
}
