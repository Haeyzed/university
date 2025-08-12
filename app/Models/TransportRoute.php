<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class TransportRoute
 * @brief Model for managing transport routes.
 *
 * This model represents the 'transport_routes' table, defining
 * various routes for student and staff transportation.
 *
 * @property int $id
 * @property string $title
 * @property float|null $fee
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class TransportRoute extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'fee',
        'description',
        'status',
    ];

    /**
     * Get the transport members on this route.
     *
     * @return HasMany
     */
    public function transportMembers(): HasMany
    {
        return $this->hasMany(TransportMember::class);
    }

    /**
     * The transport vehicles assigned to this route.
     *
     * @return BelongsToMany
     */
    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(TransportVehicle::class, 'transport_route_transport_vehicle', 'transport_route_id', 'transport_vehicle_id');
    }
}
