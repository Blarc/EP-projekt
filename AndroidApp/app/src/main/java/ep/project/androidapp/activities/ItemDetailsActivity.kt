package ep.project.androidapp.activities

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import ep.project.androidapp.R
import ep.project.androidapp.entities.Item
import kotlinx.android.synthetic.main.activity_item_details.*

class ItemDetailsActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_item_details)

        val item = intent.extras?.get("item") as Item

        itemDetailsName.text = item.name
        itemDetailsId.text = item.id.toString()
        itemDetailsDescription.text = item.description

    }
}
