package ep.project.androidapp.adapters

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.AsyncListDiffer
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.RecyclerView
import ep.project.androidapp.R
import ep.project.androidapp.entities.Item
import kotlinx.android.synthetic.main.shopping_list_single_item_layout.view.*

class ShoppingListItemsAdapter(private val interaction: Interaction? = null) :
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

        return ShoppingListItemsViewHolder(
            LayoutInflater.from(parent.context).inflate(
                R.layout.shopping_list_single_item_layout,
                parent,
                false
            ),
            interaction
        )
    }

    override fun onBindViewHolder(holder: RecyclerView.ViewHolder, position: Int) {
        when (holder) {
            is ShoppingListItemsViewHolder -> {
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

    class ShoppingListItemsViewHolder
    constructor(
        itemView: View,
        private val interaction: Interaction?
    ) : RecyclerView.ViewHolder(itemView) {

        fun bind(item: Item) = with(itemView) {
            itemView.setOnClickListener {
                interaction?.onItemSelected(adapterPosition, item)
            }

            itemView.shoppingListItemId.text = item.id.toString()
            itemView.shoppingListItemName.text = item.name
            itemView.shoppingListItemPrice.text =
                resources.getString(R.string.singleItemLayout_price, item.price * item.items_amount)
            itemView.shoppingListItemAmount.text = item.items_amount.toString()
            itemView.shoppingListItemRemoveButton.setOnClickListener {
                interaction?.removeItem(item)
            }
            itemView.shoppingListItemDecreaseButton.setOnClickListener {
                interaction?.decreaseItem(item)
            }
            itemView.shoppingListItemAddButton.setOnClickListener {
                interaction?.addItem(item)
            }
        }
    }

    interface Interaction {
        fun onItemSelected(position: Int, item: Item)

        fun removeItem(item: Item)

        fun addItem(item: Item)

        fun decreaseItem(item: Item)
    }
}

