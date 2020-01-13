package ep.project.androidapp.adapters

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import ep.project.androidapp.R
import ep.project.androidapp.entities.Item
import kotlinx.android.synthetic.main.single_item_layout.view.*

class ItemsListAdapter : RecyclerView.Adapter<RecyclerView.ViewHolder>() {

    private var items: List<Item> = ArrayList()

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecyclerView.ViewHolder {
        return ItemsViewHolder(
            LayoutInflater
                .from(parent.context)
                .inflate(
                    R.layout.single_item_layout,
                    parent,
                    false
                )
        )
    }

    override fun onBindViewHolder(holder: RecyclerView.ViewHolder, position: Int) {
        when (holder) {
            is ItemsViewHolder -> {
                holder.bind(items[position])
            }
        }
    }

    override fun getItemCount(): Int {
        return items.size
    }

    fun submitList(itemsList: List<Item>) {
        items = itemsList
    }

    class ItemsViewHolder constructor(
        itemView: View
    ) : RecyclerView.ViewHolder(itemView) {

        fun bind(item: Item) {
            itemView.itemName.text = item.name
            itemView.itemId.text = item.id.toString()
        }
    }
}