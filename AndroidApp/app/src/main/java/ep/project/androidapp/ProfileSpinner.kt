package ep.project.androidapp

import android.app.Activity
import android.content.Intent
import android.view.View
import android.widget.AdapterView
import android.widget.ArrayAdapter
import android.widget.Spinner
import android.widget.Toast
import androidx.core.content.ContextCompat.startActivity
import ep.project.androidapp.activities.ItemsListActivity
import ep.project.androidapp.activities.MainActivity
import ep.project.androidapp.activities.ProfileActivity
import ep.project.androidapp.activities.ProfileSettingsActivity
import ep.project.androidapp.enums.ProfileSpinnerEnum


class ProfileSpinner(private val activity: Activity) : AdapterView.OnItemSelectedListener {

    private var spinner: Spinner = activity.findViewById(R.id.spinnerProfile)

    init {
        val dataAdapter = ArrayAdapter<ProfileSpinnerEnum>(
            activity,
            android.R.layout.simple_spinner_item,
            ProfileSpinnerEnum.values()
        )
        dataAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinner.adapter = dataAdapter

        when (activity) {
            is ProfileActivity -> {
                spinner.setSelection(ProfileSpinnerEnum.SHOPPING_LISTS.ordinal, false)
            }
            is ProfileSettingsActivity -> {
                spinner.setSelection(ProfileSpinnerEnum.SETTINGS.ordinal, false)
            }
            else -> {
                spinner.setSelection(ProfileSpinnerEnum.ITEMS.ordinal, false)
            }
        }
        spinner.onItemSelectedListener = this
    }

    override fun onNothingSelected(parent: AdapterView<*>?) {
        Toast.makeText(activity, "Nothing selected!", Toast.LENGTH_SHORT).show()
    }

    override fun onItemSelected(parent: AdapterView<*>?, view: View?, position: Int, id: Long) {
        when (parent!!.getItemAtPosition(position)) {
            ProfileSpinnerEnum.ITEMS -> {
                val intent = Intent(activity, ItemsListActivity::class.java)
                startActivity(activity, intent, null)
            }

            ProfileSpinnerEnum.SHOPPING_LISTS -> {
                val intent = Intent(activity, ProfileActivity::class.java)
                startActivity(activity, intent, null)
            }

            ProfileSpinnerEnum.SETTINGS -> {
                val intent = Intent(activity, ProfileSettingsActivity::class.java)
                startActivity(activity, intent, null)
            }

            ProfileSpinnerEnum.LOG_OUT -> {
                (activity.application as ApplicationObject).user = null
                val intent = Intent(activity, MainActivity::class.java)
                startActivity(activity, intent, null)
            }

        }
    }
}
