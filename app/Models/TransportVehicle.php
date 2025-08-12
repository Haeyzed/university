<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class TransportVehicle
 * @brief Model for managing transport vehicles.
 *
 * This model represents the 'transport_vehicles' table, storing details
 * about vehicles used for student and staff transportation.
 *
 * @property int $id
 * @property string $number
 * @property string|null $type
 * @property string|null $model
 * @property string|null $capacity
 * @property string|null $year_made
 * @property string|null $driver_name
 * @property string|null $driver_license
 * @property string|null $driver_contact
 * @property string|null $note
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class TransportVehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'type',
        'model',
        'capacity',
        'year_made',
        'driver_name',
        'driver_license',
        'driver_contact',
        'note',
        'status',
    ];

    /**
     * Get the transport members using this vehicle.
     *
     * @return HasMany
     */
    public function transportMembers(): HasMany
    {
        return $this->hasMany(TransportMember::class);
    }

    /**
     * The transport routes assigned to this vehicle.
     *
     * @return BelongsToMany
     */
    public function transportRoutes(): BelongsToMany
    {
        return $this->belongsToMany(TransportRoute::class, 'transport_route_transport_vehicle', 'transport_vehicle_id', 'transport_route_id');
    }
}
