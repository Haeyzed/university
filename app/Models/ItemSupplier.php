<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class ItemSupplier
 * @brief Model for managing item suppliers.
 *
 * This model represents the 'item_suppliers' table, storing details
 * about various suppliers of inventory items.
 *
 * @property int $id
 * @property string $title
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $contact_person
 * @property string|null $designation
 * @property string|null $contact_person_email
 * @property string|null $contact_person_phone
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ItemSupplier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'email',
        'phone',
        'address',
        'contact_person',
        'designation',
        'contact_person_email',
        'contact_person_phone',
        'description',
        'status',
    ];

    /**
     * Get the item stocks from this supplier.
     *
     * @return HasMany
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(ItemStock::class, 'supplier_id');
    }
}
