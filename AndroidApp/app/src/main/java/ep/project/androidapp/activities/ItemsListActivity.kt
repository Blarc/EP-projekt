package ep.project.androidapp.activities

import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import ep.project.androidapp.R
import ep.project.androidapp.TopSpacingItemDecoration
import ep.project.androidapp.adapters.ItemsListAdapter
import ep.project.androidapp.entities.Item
import ep.project.androidapp.services.ItemService
import kotlinx.android.synthetic.main.activity_items_list.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class ItemsListActivity : AppCompatActivity(), ItemsListAdapter.Interaction {

    private val TAG = this.javaClass.canonicalName

    private lateinit var itemsAdapter: ItemsListAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_items_list)

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
            }
        })
    }

    private fun initRecyclerView() {
        itemsRecyclerView.apply {
            layoutManager = LinearLayoutManager(this@ItemsListActivity)
            val topSpacingItemDecoration = TopSpacingItemDecoration(30)
            addItemDecoration(topSpacingItemDecoration)
            itemsAdapter = ItemsListAdapter(this@ItemsListActivity)
            adapter = itemsAdapter
        }
    }

    override fun onItemSelected(position: Int, item: Item) {
        Toast.makeText(this, "Item name: ${item.name}", Toast.LENGTH_SHORT).show()
    }
}
