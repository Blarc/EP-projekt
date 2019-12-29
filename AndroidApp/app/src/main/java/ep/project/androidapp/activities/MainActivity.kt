package ep.project.androidapp.activities


import android.content.Intent
import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import ep.project.androidapp.R
import kotlinx.android.synthetic.main.activity_main.*


class MainActivity : AppCompatActivity() {
//    private val TAG = this.javaClass.canonicalName

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)


        loginBtn.setOnClickListener {
            val intent = Intent(this, LoginActivity::class.java)
//            intent.putExtra("moj_parameter", "Prenesena vrednost")
            startActivity(intent);
        }

        registerBtn.setOnClickListener {
            val intent = Intent(this, RegisterActivity::class.java)
            startActivity(intent);
        }

        itemsListBtn.setOnClickListener {
            val intent = Intent(this, ItemsListActivity::class.java)
            startActivity(intent);
        }
    }


}