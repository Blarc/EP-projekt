package ep.project.androidapp.activities

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import ep.project.androidapp.ApplicationObject
import ep.project.androidapp.ProfileSpinner
import ep.project.androidapp.R
import ep.project.androidapp.TopSpacingItemDecoration
import ep.project.androidapp.adapters.ItemsAdapter
import ep.project.androidapp.entities.Item
import ep.project.androidapp.entities.ShoppingList
import ep.project.androidapp.services.ItemService
import ep.project.androidapp.services.ShoppingListService
import kotlinx.android.synthetic.main.activity_items_list.*
import kotlinx.android.synthetic.main.profile_toolbar_layout.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class ItemsListActivity : AppCompatActivity(), ItemsAdapter.Interaction {

    private val TAG = this.javaClass.canonicalName

    private lateinit var appObject: ApplicationObject

    private lateinit var itemsAdapter: ItemsAdapter

    private lateinit var shoppingListsNames: MutableList<CharSequence>

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_items_list)

        appObject = (application as ApplicationObject)

        val user = appObject.user
        if (user == null) {
            spinnerProfile.visibility = View.GONE
            usernameToolbarProfile.text =
                getString(R.string.profileToolbarLayout_username, "Android", "App")
        } else {
            ProfileSpinner(this)
            spinnerProfile.visibility = View.VISIBLE
            usernameToolbarProfile.text =
                getString(R.string.profileToolbarLayout_username, user.firstName, user.lastName)

            shoppingListsNames = mutableListOf()
            appObject.user?.shoppingLists?.forEach {
                shoppingListsNames.add(it.name)
            }
        }

        itemsListLoading.visibility = View.VISIBLE
        initView()
    }

    private fun initView() {
        val call = ItemService.instance.getAll()
        call.enqueue(object : Callback<List<Item>> {
            override fun onResponse(call: Call<List<Item>>, response: Response<List<Item>>) {
                initRecyclerView()
                itemsAdapter.submitList(response.body()!!)
                itemsListLoading.visibility = View.GONE
            }

            override fun onFailure(call: Call<List<Item>>, t: Throwable) {
                Log.e(TAG, "Error: ${t.message}")
                Toast.makeText(this@ItemsListActivity, "Error: ${t.message}", Toast.LENGTH_LONG)
                    .show()
            }
        })
    }

    private fun initRecyclerView() {
        itemsRecyclerView.apply {
            layoutManager = LinearLayoutManager(this@ItemsListActivity)
            val topSpacingItemDecoration = TopSpacingItemDecoration(30)
            addItemDecoration(topSpacingItemDecoration)
            itemsAdapter = ItemsAdapter(this@ItemsListActivity)
            adapter = itemsAdapter
        }
    }

    override fun onItemSelected(position: Int, item: Item) {

        val call = ItemService.instance.get(item.id)
        call.enqueue(object : Callback<Item> {
            override fun onResponse(call: Call<Item>, response: Response<Item>) {
                val intent = Intent(this@ItemsListActivity, ItemDetailsActivity::class.java)
                intent.putExtra("item", response.body()!!)
                startActivity(intent);
            }

            override fun onFailure(call: Call<Item>, t: Throwable) {
                Log.e(TAG, "Error: ${t.message}")
                Toast.makeText(this@ItemsListActivity, "Error: ${t.message}", Toast.LENGTH_LONG)
                    .show()
            }
        })
    }

    override fun loggedIn(): Boolean {
        return appObject.user != null
    }

    override fun addItem(item: Item) {

        val builder = AlertDialog.Builder(this)
        with(builder) {
            setTitle("Pick a shopping list")

            builder.setItems(shoppingListsNames.toTypedArray()) { _, i ->
                val shoppingList = appObject.user?.shoppingLists!![i]
                val call = ShoppingListService.instance.addItem(shoppingList.id, item)
                call.enqueue(object : Callback<ShoppingList> {
                    override fun onResponse(
                        call: Call<ShoppingList>,
                        response: Response<ShoppingList>
                    ) {
                        Toast.makeText(
                            this@ItemsListActivity,
                            "Added item to shopping list",
                            Toast.LENGTH_LONG
                        ).show()
                    }

                    override fun onFailure(call: Call<ShoppingList>, t: Throwable) {
                        Toast.makeText(
                            this@ItemsListActivity,
                            "Failed to add item: ${t.message}",
                            Toast.LENGTH_LONG
                        ).show()
                    }
                })
            }

            builder.setNegativeButton("Cancel") { dialog, _ ->
                dialog.cancel()
            }

            show()
        }
    }
}
