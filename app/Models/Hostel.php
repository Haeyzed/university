<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class Hostel
 * @brief Model for managing hostels.
 *
 * This model represents the 'hostels' table, storing details
 * about different hostel facilities.
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property string|null $capacity
 * @property string|null $warden_name
 * @property string|null $warden_contact
 * @property string|null $address
 * @property string|null $note
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Hostel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'capacity',
        'warden_name',
        'warden_contact',
        'address',
        'note',
        'status',
    ];

    /**
     * Get the hostel rooms in this hostel.
     *
     * @return HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(HostelRoom::class);
    }
}
