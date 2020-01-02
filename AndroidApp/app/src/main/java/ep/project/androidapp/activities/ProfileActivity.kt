package ep.project.androidapp.activities

import android.os.Bundle
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import ep.project.androidapp.ApplicationObject
import ep.project.androidapp.ProfileSpinner
import ep.project.androidapp.R
import ep.project.androidapp.TopSpacingItemDecoration
import ep.project.androidapp.adapters.ShoppingListsAdapter
import ep.project.androidapp.entities.ShoppingList
import kotlinx.android.synthetic.main.activity_profile.*

class ProfileActivity : AppCompatActivity(), ShoppingListsAdapter.Interaction {

    private lateinit var shoppingListsAdapter: ShoppingListsAdapter


    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_profile)
        ProfileSpinner(this)

        loadingProfile.visibility = View.VISIBLE

        val appObject = application as ApplicationObject
        if (appObject.loggedIn) {

            val user = appObject.user!!

            idProfile.text = user.id.toString()
            nameProfile.text =
                getString(R.string.profileActivity_name, user.firstName, user.lastName)
            emailProfile.text = user.email
            initRecyclerView()
            shoppingListsAdapter.submitList(user.shoppingLists)
            loadingProfile.visibility = View.GONE

        }

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
        Toast.makeText(this, "Clicked on: ${item.name}!", Toast.LENGTH_LONG).show()
    }
}
