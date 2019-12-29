package ep.project.androidapp.activities

import android.os.Bundle
import android.util.Log
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

    private val TAG = RegisterActivity::class.java.canonicalName

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_register)

        registerButton.setOnClickListener {

            registerLoading.visibility = View.VISIBLE
            registerButton.visibility = View.GONE

            val password = registerPassword.text.trim().toString()
            val passwordConfirm = registerPasswordConfirm.text.trim().toString()

            if (password == passwordConfirm) {
                UserService.instance.register(
                    registerName.text.trim().toString(),
                    registerEmail.text.trim().toString(),
                    password,
                    passwordConfirm
                ).enqueue(this)
            } else {
                registerLoading.visibility = View.GONE
                registerButton.visibility = View.VISIBLE
                Toast.makeText(this, "Password doesn't match!", Toast.LENGTH_LONG).show()
            }
        }
    }

    override fun onResponse(call: Call<User>, response: Response<User>) {
        Toast.makeText(this, "Success!", Toast.LENGTH_LONG).show()

        registerLoading.visibility = View.GONE
        registerButton.visibility = View.VISIBLE

        (application as ApplicationObject).user = response.body()

//        TODO
//        val intent = Intent(this, LoginActivity::class.java)
//        startActivity(intent);

    }

    override fun onFailure(call: Call<User>, t: Throwable) {
        Log.e(TAG, "Error: ${t.message}")
        Toast.makeText(this, "Error: ${t.message}!", Toast.LENGTH_LONG).show()

        registerLoading.visibility = View.GONE
        registerButton.visibility = View.VISIBLE

        (application as ApplicationObject).user = null;
    }
}