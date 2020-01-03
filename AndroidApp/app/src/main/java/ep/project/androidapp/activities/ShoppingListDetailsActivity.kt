package ep.project.androidapp.activities

import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import ep.project.androidapp.R
import ep.project.androidapp.TopSpacingItemDecoration
import ep.project.androidapp.adapters.ItemsAdapter
import ep.project.androidapp.entities.Item
import ep.project.androidapp.entities.ShoppingList
import kotlinx.android.synthetic.main.activity_shopping_list_details.*
import kotlinx.android.synthetic.main.shopping_list_details_toolbar_layout.*

class ShoppingListDetailsActivity : AppCompatActivity(), ItemsAdapter.Interaction {

    private lateinit var itemsAdapter: ItemsAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_shopping_list_details)

        val shoppingList = intent.extras?.get("shoppingList") as ShoppingList

        shoppingListDetailsName.text = shoppingList.name

        initRecyclerView()
        itemsAdapter.submitList(shoppingList.items)

    }

    private fun initRecyclerView() {
        shoppingListDetailsItems.apply {
            layoutManager = LinearLayoutManager(this@ShoppingListDetailsActivity)
            val topSpacingItemDecoration = TopSpacingItemDecoration(30)
            addItemDecoration(topSpacingItemDecoration)
            itemsAdapter = ItemsAdapter(this@ShoppingListDetailsActivity)
            adapter = itemsAdapter
        }
    }

    override fun onItemSelected(position: Int, item: Item) {
        Toast.makeText(this, "Clicked on: ${item.name}", Toast.LENGTH_LONG).show()
    }
}
