package ep.project.androidapp.activities

import android.content.Intent
import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import ep.project.androidapp.R
import ep.project.androidapp.TopSpacingItemDecoration
import ep.project.androidapp.adapters.CheckoutAdapter
import ep.project.androidapp.entities.Item
import ep.project.androidapp.entities.ShoppingList
import ep.project.androidapp.services.ItemService
import ep.project.androidapp.services.ShoppingListService
import kotlinx.android.synthetic.main.activity_checkout.*
import kotlinx.android.synthetic.main.basic_toolbar_layout.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class CheckoutActivity : AppCompatActivity(), CheckoutAdapter.Interaction {

    private lateinit var checkoutAdapter: CheckoutAdapter

    private lateinit var shoppingList: ShoppingList

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_checkout)

        basicToolbarName.text = "Checkout"

        shoppingList = intent.extras?.get("shoppingList") as ShoppingList

        initRecyclerView()
        checkoutAdapter.submitList(shoppingList.items)

        totalAmount.text = getString(
            R.string.singleItemLayout_price,
            shoppingList.items.sumByDouble { it.price.toDouble() * it.items_amount.toDouble() }.toFloat()
        )

        checkoutBuyButton.setOnClickListener {
            setShoppingListStatus()
        }
    }

    private fun setShoppingListStatus() {
        val call = ShoppingListService.instance.update(
            shoppingList.id,
            ShoppingList(shoppingList.id, shoppingList.name, 0, shoppingList.items)
        )
        call.enqueue(object : Callback<ShoppingList> {
            override fun onResponse(call: Call<ShoppingList>, response: Response<ShoppingList>) {
                Toast.makeText(this@CheckoutActivity, "Successful!", Toast.LENGTH_LONG)
                    .show()
                val intent = Intent(this@CheckoutActivity, ProfileActivity::class.java)
                startActivity(intent)
            }

            override fun onFailure(call: Call<ShoppingList>, t: Throwable) {
                Toast.makeText(this@CheckoutActivity, "Error: ${t.message}", Toast.LENGTH_LONG)
                    .show()
            }
        })
    }

    private fun initRecyclerView() {
        checkoutItemList.apply {
            layoutManager = LinearLayoutManager(this@CheckoutActivity)
            val topSpacingItemDecoration = TopSpacingItemDecoration(30)
            addItemDecoration(topSpacingItemDecoration)
            checkoutAdapter = CheckoutAdapter(this@CheckoutActivity)
            adapter = checkoutAdapter
        }
    }

    override fun onItemSelected(position: Int, item: Item) {

        val call = ItemService.instance.get(item.id)
        call.enqueue(object : Callback<Item> {
            override fun onResponse(call: Call<Item>, response: Response<Item>) {
                val intent = Intent(this@CheckoutActivity, ItemDetailsActivity::class.java)
                intent.putExtra("item", response.body()!!)
                startActivity(intent);
            }

            override fun onFailure(call: Call<Item>, t: Throwable) {
                Toast.makeText(this@CheckoutActivity, "Error: ${t.message}", Toast.LENGTH_LONG)
                    .show()
            }
        })
    }
}
