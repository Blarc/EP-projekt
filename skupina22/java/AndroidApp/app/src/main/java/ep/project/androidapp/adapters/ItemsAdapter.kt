package ep.project.androidapp.adapters

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.AsyncListDiffer
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.RecyclerView
import ep.project.androidapp.R
import ep.project.androidapp.entities.Item
import kotlinx.android.synthetic.main.items_list_single_item_layout.view.*

class ItemsAdapter(private val interaction: Interaction? = null) :
    RecyclerView.Adapter<RecyclerView.ViewHolder>() {

    val DIFF_CALLBACK = object : DiffUtil.ItemCallback<Item>() {

        override fun areItemsTheSame(oldItem: Item, newItem: Item): Boolean {
            return oldItem.id == newItem.id
        }

        override fun areContentsTheSame(oldItem: Item, newItem: Item): Boolean {
            return oldItem == newItem
        }

    }
    private val differ = AsyncListDiffer(this, DIFF_CALLBACK)

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecyclerView.ViewHolder {

        return ItemViewHolder(
            LayoutInflater.from(parent.context).inflate(
                R.layout.items_list_single_item_layout,
                parent,
                false
            ),
            interaction
        )
    }

    override fun onBindViewHolder(holder: RecyclerView.ViewHolder, position: Int) {
        when (holder) {
            is ItemViewHolder -> {
                holder.bind(differ.currentList[position])
            }
        }
    }

    override fun getItemCount(): Int {
        return differ.currentList.size
    }

    fun submitList(list: List<Item>) {
        differ.submitList(list)
    }

    class ItemViewHolder
    constructor(
        itemView: View,
        private val interaction: Interaction?
    ) : RecyclerView.ViewHolder(itemView) {

        fun bind(item: Item) = with(itemView) {
            itemView.setOnClickListener {
                interaction?.onItemSelected(adapterPosition, item)
            }

            itemView.itemListItemId.text = item.id.toString()
            itemView.itemListItemName.text = item.name
            itemView.itemListItemPrice.text =
                resources.getString(R.string.singleItemLayout_price, item.price)


            itemView.itemListAddButton.setOnClickListener {
                interaction?.addItem(item)
            }

            if (interaction?.loggedIn()!!) {
                itemView.itemListAddButton.visibility = View.VISIBLE
            } else {
                itemView.itemListAddButton.visibility = View.GONE
            }
        }
    }

    interface Interaction {

        fun onItemSelected(position: Int, item: Item)

        fun loggedIn(): Boolean

        fun addItem(item: Item)
    }
}