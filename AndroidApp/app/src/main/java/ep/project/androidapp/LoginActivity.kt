package ep.project.androidapp

import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import ep.project.androidapp.entities.User
import ep.project.androidapp.services.UserService
import kotlinx.android.synthetic.main.activity_login.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response


class LoginActivity : AppCompatActivity(), Callback<User> {

    private val TAG = RegisterActivity::class.java.canonicalName

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        loginButton.setOnClickListener {

            loginLoading.visibility = View.VISIBLE
            loginButton.visibility = View.GONE

            // TODO validator
            UserService.instance.login(
                loginEmail.text.trim().toString(),
                loginPassword.text.trim().toString()
            ).enqueue(this)
        }
    }

    override fun onResponse(call: Call<User>, response: Response<User>) {
        Toast.makeText(this, "Success!", Toast.LENGTH_SHORT).show()

        loginLoading.visibility = View.GONE
        loginButton.visibility = View.VISIBLE
    }

    override fun onFailure(call: Call<User>, t: Throwable) {
        Log.e(TAG, "Error: ${t.message}")
        Toast.makeText(this, "Error: ${t.message}!", Toast.LENGTH_SHORT).show()

        loginLoading.visibility = View.GONE
        loginButton.visibility = View.VISIBLE
    }
}
