<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /**
             * The unique identifier for the item.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the item category.
             * @var int $item_category_id
             * @example 1
             */
            'item_category_id' => $this->item_category_id,

            /**
             * The title of the item category.
             * @var string|null $item_category_title
             * @example "Electronics"
             */
            'item_category_title' => $this->whenLoaded('itemCategory', fn() => $this->itemCategory->title),

            /**
             * The ID of the item supplier.
             * @var int $item_supplier_id
             * @example 1
             */
            'item_supplier_id' => $this->item_supplier_id,

            /**
             * The name of the item supplier.
             * @var string|null $item_supplier_name
             * @example "Tech Supplies Inc."
             */
            'item_supplier_name' => $this->whenLoaded('itemSupplier', fn() => $this->itemSupplier->name),

            /**
             * The ID of the item store.
             * @var int $item_store_id
             * @example 1
             */
            'item_store_id' => $this->item_store_id,

            /**
             * The name of the item store.
             * @var string|null $item_store_name
             * @example "Main Store"
             */
            'item_store_name' => $this->whenLoaded('itemStore', fn() => $this->itemStore->title),

            /**
             * The title or name of the item.
             * @var string $title
             * @example "Laptop"
             */
            'title' => $this->title,

            /**
             * The unit of measurement for the item.
             * @var string|null $unit
             * @example "pcs"
             */
            'unit' => $this->unit,

            /**
             * The description of the item.
             * @var string|null $description
             * @example "High-performance laptop for academic use."
             */
            'description' => $this->description,

            /**
             * The status of the item (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

            /**
             * The ID of the user who created the record.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the record.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the record.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the record.
             * @var string|null $updated_by_name
             * @example "Admin User"
             */
            'updated_by_name' => $this->whenLoaded('updatedBy', fn() => $this->updatedBy->name),

            /**
             * The timestamp when the record was created.
             * @var string $created_at
             * @example "2024-07-19 12:00:00"
             */
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),

            /**
             * The timestamp when the record was last updated.
             * @var string $updated_at
             * @example "2024-07-19 12:30:00"
             */
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
