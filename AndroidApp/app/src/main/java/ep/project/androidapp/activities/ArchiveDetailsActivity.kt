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
import kotlinx.android.synthetic.main.activity_archive_details.*
import kotlinx.android.synthetic.main.basic_toolbar_layout.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class ArchiveDetailsActivity : AppCompatActivity(), CheckoutAdapter.Interaction {

    private lateinit var checkoutAdapter: CheckoutAdapter

    private lateinit var shoppingList: ShoppingList

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_archive_details)

        basicToolbarName.text = "Archive details"

        shoppingList = intent.extras?.get("shoppingList") as ShoppingList

        initRecyclerView()
        checkoutAdapter.submitList(shoppingList.items)
    }

    private fun initRecyclerView() {
        archiveDetailsItemList.apply {
            layoutManager = LinearLayoutManager(this@ArchiveDetailsActivity)
            val topSpacingItemDecoration = TopSpacingItemDecoration(30)
            addItemDecoration(topSpacingItemDecoration)
            checkoutAdapter = CheckoutAdapter(this@ArchiveDetailsActivity)
            adapter = checkoutAdapter
        }
    }

    override fun onItemSelected(position: Int, item: Item) {
        val call = ItemService.instance.get(item.id)
        call.enqueue(object : Callback<Item> {
            override fun onResponse(call: Call<Item>, response: Response<Item>) {
                val intent = Intent(this@ArchiveDetailsActivity, ItemDetailsActivity::class.java)
                intent.putExtra("item", response.body()!!)
                startActivity(intent);
            }

            override fun onFailure(call: Call<Item>, t: Throwable) {
                Toast.makeText(
                    this@ArchiveDetailsActivity,
                    "Error: ${t.message}",
                    Toast.LENGTH_LONG
                )
                    .show()
            }
        })
    }
}
