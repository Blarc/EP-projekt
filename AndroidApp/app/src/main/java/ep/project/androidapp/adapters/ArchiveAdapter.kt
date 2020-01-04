package ep.project.androidapp.adapters

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.AsyncListDiffer
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.RecyclerView
import ep.project.androidapp.R
import ep.project.androidapp.entities.ShoppingList
import ep.project.androidapp.enums.ShoppingListStatusEnum
import kotlinx.android.synthetic.main.single_archive_item_layout.view.*

class ArchiveAdapter(private val interaction: Interaction? = null) :
    RecyclerView.Adapter<RecyclerView.ViewHolder>() {

    val DIFF_CALLBACK = object : DiffUtil.ItemCallback<ShoppingList>() {

        override fun areItemsTheSame(oldItem: ShoppingList, newItem: ShoppingList): Boolean {
            return oldItem.id == newItem.id

        }

        override fun areContentsTheSame(oldItem: ShoppingList, newItem: ShoppingList): Boolean {
            return oldItem == newItem

        }

    }
    private val differ = AsyncListDiffer(this, DIFF_CALLBACK)


    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecyclerView.ViewHolder {

        return ArchiveViewHolder(
            LayoutInflater.from(parent.context).inflate(
                R.layout.single_archive_item_layout,
                parent,
                false
            ),
            interaction
        )
    }

    override fun onBindViewHolder(holder: RecyclerView.ViewHolder, position: Int) {
        when (holder) {
            is ArchiveViewHolder -> {
                holder.bind(differ.currentList[position])
            }
        }
    }

    override fun getItemCount(): Int {
        return differ.currentList.size
    }

    fun submitList(list: List<ShoppingList>) {
        differ.submitList(list)
    }

    class ArchiveViewHolder
    constructor(
        itemView: View,
        private val interaction: Interaction?
    ) : RecyclerView.ViewHolder(itemView) {

        fun bind(item: ShoppingList) = with(itemView) {
            itemView.setOnClickListener {
                interaction?.onItemSelected(adapterPosition, item)
            }

            archiveItemId.text = item.id.toString()
            archiveItemName.text = item.name
            archiveItemPrice.text =
                resources.getString(
                    R.string.singleItemLayout_price,
                    item.items.sumByDouble { it.price.toDouble() * it.items_amount.toDouble() }.toFloat()
                )

            when (item.status) {
                0 -> {
                    archiveItemStatus.setBackgroundResource(R.drawable.status_background_yellow)
                    archiveItemStatus.text = ShoppingListStatusEnum.UNPROCESSED.text
                }
                1 -> {
                    archiveItemStatus.setBackgroundResource(R.drawable.status_background_green)
                    archiveItemStatus.text = ShoppingListStatusEnum.PROCESSED.text
                }
                2 -> {
                    archiveItemStatus.setBackgroundResource(R.drawable.status_background_red)
                    archiveItemStatus.text = ShoppingListStatusEnum.CANCELED.text
                }
            }
        }
    }

    interface Interaction {
        fun onItemSelected(position: Int, item: ShoppingList)
    }
}

