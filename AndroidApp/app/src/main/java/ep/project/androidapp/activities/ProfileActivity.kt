package ep.project.androidapp.activities

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.EditText
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import ep.project.androidapp.ApplicationObject
import ep.project.androidapp.ProfileSpinner
import ep.project.androidapp.R
import ep.project.androidapp.TopSpacingItemDecoration
import ep.project.androidapp.adapters.ShoppingListsAdapter
import ep.project.androidapp.entities.ShoppingList
import ep.project.androidapp.entities.User
import ep.project.androidapp.services.ShoppingListService
import ep.project.androidapp.services.UserService
import kotlinx.android.synthetic.main.activity_profile.*
import kotlinx.android.synthetic.main.profile_toolbar_layout.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response


class ProfileActivity : AppCompatActivity(), ShoppingListsAdapter.Interaction {

    private lateinit var appObject: ApplicationObject
    private lateinit var shoppingListsAdapter: ShoppingListsAdapter
    private lateinit var user: User

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_profile)
        ProfileSpinner(this)

        loadingProfile.visibility = View.VISIBLE

        appObject = (application as ApplicationObject)

        if (appObject.user != null) {

            user = appObject.user!!
            refreshProfile()
            usernameToolbarProfile.text =
                getString(R.string.profileToolbarLayout_username, user.firstName, user.lastName)
            initRecyclerView()
            shoppingListsAdapter.submitList(user.shoppingLists)
            loadingProfile.visibility = View.GONE

        }

        newShoppingListButton.setOnClickListener {

            val builder = AlertDialog.Builder(this)
            val inflater = layoutInflater
            builder.setTitle("New shopping list's name: ")
            val dialogLayout = inflater.inflate(R.layout.alert_dialog_edittext, null)
            val editText = dialogLayout.findViewById<EditText>(R.id.alertDialogEditText)
            builder.setView(dialogLayout)
            builder.setPositiveButton("Create") { _, _ ->
                loadingProfile.visibility = View.VISIBLE
                addNewShoppingList(editText.text.trim().toString())
            }
            builder.setNegativeButton("Cancel") { dialog, _ ->
                dialog.cancel()
            }
            builder.show()
        }

        refreshProfileButton.setOnClickListener {
            Toast.makeText(this, "Refreshed!", Toast.LENGTH_SHORT).show()
            refreshProfile()
        }

    }

    private fun addNewShoppingList(name: String) {
        val call = ShoppingListService.instance.insert("Bearer ${user.apiToken}", name)
        call.enqueue(object : Callback<ShoppingList> {
            override fun onResponse(call: Call<ShoppingList>, response: Response<ShoppingList>) {
                loadingProfile.visibility = View.GONE
                Toast.makeText(
                    this@ProfileActivity,
                    "Created!",
                    Toast.LENGTH_SHORT
                ).show();
                refreshProfile()
            }

            override fun onFailure(call: Call<ShoppingList>, t: Throwable) {
                Toast.makeText(
                    this@ProfileActivity,
                    "Failed to create: ${t.message}",
                    Toast.LENGTH_LONG
                ).show();
            }
        })
    }

    private fun refreshProfile() {
        val call = UserService.instance.getCurrent("Bearer ${user.apiToken}")
        call.enqueue(object : Callback<User> {
            override fun onResponse(call: Call<User>, response: Response<User>) {
                appObject.user = response.body()!!
                user = response.body()!!
                shoppingListsAdapter.submitList(user.shoppingLists)
            }

            override fun onFailure(call: Call<User>, t: Throwable) {
                Toast.makeText(
                    this@ProfileActivity,
                    "Failed to refresh: ${t.message}",
                    Toast.LENGTH_LONG
                ).show();
            }
        })
    }

    private fun initRecyclerView() {
        shoppingListsProfile.apply {
            layoutManager = LinearLayoutManager(this@ProfileActivity)
            val topSpacingItemDecoration = TopSpacingItemDecoration(30)
            addItemDecoration(topSpacingItemDecoration)
            shoppingListsAdapter = ShoppingListsAdapter(this@ProfileActivity)
            adapter = shoppingListsAdapter
        }
    }

    override fun onItemSelected(position: Int, item: ShoppingList) {
        loadingProfile.visibility = View.VISIBLE
        val call = ShoppingListService.instance.get(item.id)
        call.enqueue(object : Callback<ShoppingList> {
            override fun onResponse(call: Call<ShoppingList>, response: Response<ShoppingList>) {
                val intent = Intent(this@ProfileActivity, ShoppingListDetailsActivity::class.java)
                intent.putExtra("shoppingList", response.body()!!)
                startActivity(intent);
                loadingProfile.visibility = View.GONE
            }

            override fun onFailure(call: Call<ShoppingList>, t: Throwable) {
                Toast.makeText(this@ProfileActivity, "Error: ${t.message}", Toast.LENGTH_LONG)
                    .show()
                loadingProfile.visibility = View.GONE
            }
        })
    }
}
