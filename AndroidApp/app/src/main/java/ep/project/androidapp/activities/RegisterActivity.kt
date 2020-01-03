package ep.project.androidapp.activities

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import ep.project.androidapp.ApplicationObject
import ep.project.androidapp.R
import ep.project.androidapp.entities.User
import ep.project.androidapp.services.UserService
import kotlinx.android.synthetic.main.activity_register.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class RegisterActivity : AppCompatActivity(), Callback<User> {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_register)
        setSupportActionBar(findViewById(R.id.registerToolbar))

        registerButton.setOnClickListener {

            registerLoading.visibility = View.VISIBLE
            registerButton.visibility = View.GONE

            val password = registerPassword.text.trim().toString()
            val passwordConfirm = registerPasswordConfirm.text.trim().toString()

            if (password.length >= 8 && password == passwordConfirm) {
                UserService.instance.register(
                    registerFirstName.text.trim().toString(),
                    registerLastName.text.trim().toString(),
                    registerEmail.text.trim().toString(),
                    password,
                    passwordConfirm,
                    registerTelephone.text.toString(),
                    registerStreet.text.trim().toString(),
                    registerPost.text.trim().toString(),
                    registerPostCode.text.trim().toString()
                ).enqueue(this)
            } else {
                registerLoading.visibility = View.GONE
                registerButton.visibility = View.VISIBLE
                Toast.makeText(this, "Password is too short or doesn't match!", Toast.LENGTH_LONG)
                    .show()
            }
        }
    }

    override fun onResponse(call: Call<User>, response: Response<User>) {
        Toast.makeText(this, "Success!", Toast.LENGTH_LONG).show()

        registerLoading.visibility = View.GONE
        registerButton.visibility = View.VISIBLE

        (application as ApplicationObject).user = response.body()

        val intent = Intent(this, ProfileActivity::class.java)
        startActivity(intent);
    }

    override fun onFailure(call: Call<User>, t: Throwable) {
        Toast.makeText(this, "Error: ${t.message}!", Toast.LENGTH_LONG).show()

        registerLoading.visibility = View.GONE
        registerButton.visibility = View.VISIBLE

        (application as ApplicationObject).user = null;
    }
}
