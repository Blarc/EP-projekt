package ep.project.androidapp.activities

import android.content.Intent
import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import ep.project.androidapp.ApplicationObject
import ep.project.androidapp.ProfileSpinner
import ep.project.androidapp.R
import ep.project.androidapp.TopSpacingItemDecoration
import ep.project.androidapp.adapters.ArchiveAdapter
import ep.project.androidapp.entities.ShoppingList
import ep.project.androidapp.entities.User
import ep.project.androidapp.enums.ShoppingListStatusEnum
import ep.project.androidapp.services.UserService
import kotlinx.android.synthetic.main.activity_archive.*
import kotlinx.android.synthetic.main.archive_toolbar_layout.*
import retrofit2.Call
import retrofit2.Response

class ArchiveActivity : AppCompatActivity(), ArchiveAdapter.Interaction {

    private lateinit var archiveAdapter: ArchiveAdapter

    private lateinit var appObject: ApplicationObject

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_archive)

        appObject = (application as ApplicationObject)

        initRecyclerView()
        refreshProfile()

        archiveToolbarName.text = "Archive"
        ProfileSpinner(this)

        calculateTotal()

        archiveToolbarRefreshButton.setOnClickListener {
            refreshProfile()
        }
    }

    private fun calculateTotal() {
        archiveTotalAmount.text = getString(
            R.string.singleItemLayout_price, appObject.user!!.shoppingLists
                .filter { it.status == ShoppingListStatusEnum.PROCESSED.value }
                .sumByDouble { shoppingList ->
                    shoppingList.items
                        .sumByDouble { it.price.toDouble() * it.items_amount }
                }.toFloat()
        )
    }

    private fun refreshProfile() {
        val call = UserService.instance.getCurrent("Bearer ${appObject.user!!.apiToken}")
        call.enqueue(object : retrofit2.Callback<User> {
            override fun onResponse(call: Call<User>, response: Response<User>) {
                appObject.user = response.body()!!
                archiveAdapter.submitList(appObject.user!!.shoppingLists.filter { it.status != ShoppingListStatusEnum.ACTIVE.value })
                calculateTotal()
            }

            override fun onFailure(call: Call<User>, t: Throwable) {
                Toast.makeText(
                    this@ArchiveActivity,
                    "Failed to refresh: ${t.message}",
                    Toast.LENGTH_LONG
                ).show();
            }
        })
    }

    private fun initRecyclerView() {
        archiveItemList.apply {
            layoutManager = LinearLayoutManager(this@ArchiveActivity)
            val topSpacingItemDecoration = TopSpacingItemDecoration(30)
            addItemDecoration(topSpacingItemDecoration)
            archiveAdapter = ArchiveAdapter(this@ArchiveActivity)
            adapter = archiveAdapter
        }
    }

    override fun onItemSelected(position: Int, item: ShoppingList) {
        val intent = Intent(this, ArchiveDetailsActivity::class.java)
        intent.putExtra("shoppingList", item)
        startActivity(intent)
    }
}
