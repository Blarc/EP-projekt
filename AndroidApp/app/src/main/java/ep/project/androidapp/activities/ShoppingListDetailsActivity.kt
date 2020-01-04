package ep.project.androidapp.activities

import android.content.Intent
import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import ep.project.androidapp.R
import ep.project.androidapp.TopSpacingItemDecoration
import ep.project.androidapp.adapters.ShoppingListItemsAdapter
import ep.project.androidapp.entities.Item
import ep.project.androidapp.entities.ShoppingList
import ep.project.androidapp.services.ItemService
import ep.project.androidapp.services.ShoppingListService
import kotlinx.android.synthetic.main.activity_shopping_list_details.*
import kotlinx.android.synthetic.main.shopping_list_details_toolbar_layout.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class ShoppingListDetailsActivity : AppCompatActivity(), ShoppingListItemsAdapter.Interaction {

    private lateinit var itemsAdapter: ShoppingListItemsAdapter

    private lateinit var shoppingList: ShoppingList

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_shopping_list_details)

        shoppingList = intent.extras?.get("shoppingList") as ShoppingList

        shoppingListDetailsName.text = shoppingList.name

        initRecyclerView()
        itemsAdapter.submitList(shoppingList.items)

        shoppingListDetailsDeleteButton.setOnClickListener() {
            deleteShoppingList()
        }

        shoppingListDetailsRefreshButton.setOnClickListener {
            refreshShoppingList()
        }

    }

    private fun refreshShoppingList() {
        val call = ShoppingListService.instance.get(shoppingList.id)
        call.enqueue(object : Callback<ShoppingList> {
            override fun onResponse(call: Call<ShoppingList>, response: Response<ShoppingList>) {
                shoppingList = response.body()!!
                itemsAdapter.submitList(shoppingList.items)
                Toast.makeText(
                    this@ShoppingListDetailsActivity,
                    "Refreshed!",
                    Toast.LENGTH_LONG
                ).show();
            }

            override fun onFailure(call: Call<ShoppingList>, t: Throwable) {
                Toast.makeText(
                    this@ShoppingListDetailsActivity,
                    "Failed to refresh shopping list: ${t.message}",
                    Toast.LENGTH_LONG
                ).show();
            }
        })
    }

    private fun initRecyclerView() {
        shoppingListDetailsItems.apply {
            layoutManager = LinearLayoutManager(this@ShoppingListDetailsActivity)
            val topSpacingItemDecoration = TopSpacingItemDecoration(30)
            addItemDecoration(topSpacingItemDecoration)
            itemsAdapter = ShoppingListItemsAdapter(this@ShoppingListDetailsActivity)
            adapter = itemsAdapter
        }
    }

    private fun deleteShoppingList() {
        val call = ShoppingListService.instance.delete(shoppingList.id)
        call.enqueue(object : Callback<Void> {
            override fun onResponse(call: Call<Void>, response: Response<Void>) {
                val intent = Intent(this@ShoppingListDetailsActivity, ProfileActivity::class.java)
                startActivity(intent)
            }

            override fun onFailure(call: Call<Void>, t: Throwable) {
                Toast.makeText(
                    this@ShoppingListDetailsActivity,
                    "Failed to delete shopping list: ${t.message}",
                    Toast.LENGTH_LONG
                ).show();
            }
        })
    }

    override fun onItemSelected(position: Int, item: Item) {
        val call = ItemService.instance.get(item.id)
        call.enqueue(object : Callback<Item> {
            override fun onResponse(call: Call<Item>, response: Response<Item>) {
                val intent =
                    Intent(this@ShoppingListDetailsActivity, ItemDetailsActivity::class.java)
                intent.putExtra("item", response.body()!!)
                startActivity(intent);
            }

            override fun onFailure(call: Call<Item>, t: Throwable) {
                Toast.makeText(
                    this@ShoppingListDetailsActivity,
                    "Error: ${t.message}",
                    Toast.LENGTH_LONG
                )
                    .show()
            }
        })
    }

    override fun removeItem(item: Item) {
        val builder = AlertDialog.Builder(this)
        builder.setTitle("Warning")
        builder.setMessage("Are you sure you want to remove ${item.name}?")

        builder.setPositiveButton("YES") { _, _ ->
            val call = ShoppingListService.instance.removeItem(shoppingList.id, item)
            call.enqueue(object : Callback<ShoppingList> {
                override fun onResponse(
                    call: Call<ShoppingList>,
                    response: Response<ShoppingList>
                ) {
                    shoppingList = response.body()!!
                    itemsAdapter.submitList(shoppingList.items)
                }

                override fun onFailure(call: Call<ShoppingList>, t: Throwable) {
                    Toast.makeText(
                        this@ShoppingListDetailsActivity,
                        "Failed to remove items: ${t.message}",
                        Toast.LENGTH_LONG
                    ).show();
                }
            })
        }

        builder.setNegativeButton("NO") { dialog, _ ->
            dialog.cancel()
        }

        builder.show()
    }

    override fun addItem(item: Item) {
        val call = ShoppingListService.instance.addItem(shoppingList.id, item)
        call.enqueue(object : Callback<ShoppingList> {
            override fun onResponse(call: Call<ShoppingList>, response: Response<ShoppingList>) {
                shoppingList = response.body()!!
                itemsAdapter.submitList(shoppingList.items)
            }

            override fun onFailure(call: Call<ShoppingList>, t: Throwable) {
                Toast.makeText(
                    this@ShoppingListDetailsActivity,
                    "Failed to add item: ${t.message}",
                    Toast.LENGTH_LONG
                ).show();
            }
        })
    }

    override fun decreaseItem(item: Item) {
        val call = ShoppingListService.instance.decreaseItem(shoppingList.id, item)
        call.enqueue(object : Callback<ShoppingList> {
            override fun onResponse(call: Call<ShoppingList>, response: Response<ShoppingList>) {
                shoppingList = response.body()!!
                itemsAdapter.submitList(shoppingList.items)
            }

            override fun onFailure(call: Call<ShoppingList>, t: Throwable) {
                Toast.makeText(
                    this@ShoppingListDetailsActivity,
                    "Failed to decrease item: ${t.message}",
                    Toast.LENGTH_LONG
                ).show();
            }
        })
    }
}
