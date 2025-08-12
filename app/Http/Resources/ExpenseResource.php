<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ExpenseResource extends JsonResource
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
             * The unique identifier for the expense.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the expense category.
             * @var int $expense_category_id
             * @example 1
             */
            'expense_category_id' => $this->expense_category_id,

            /**
             * The title of the expense category.
             * @var string|null $expense_category_title
             * @example "Office Supplies"
             */
            'expense_category_title' => $this->whenLoaded('expenseCategory', fn() => $this->expenseCategory->title),

            /**
             * The title or description of the expense.
             * @var string $title
             * @example "Purchase of printer paper"
             */
            'title' => $this->title,

            /**
             * The amount of the expense.
             * @var float $amount
             * @example 50.75
             */
            'amount' => $this->amount,

            /**
             * The date of the expense.
             * @var string $date
             * @example "2024-07-17"
             */
            'date' => $this->date,

            /**
             * The status of the expense (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

            /**
             * The ID of the user who created the expense.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the expense.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the expense.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the expense.
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
