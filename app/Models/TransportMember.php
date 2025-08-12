<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @class TransportMember
 * @brief Model for managing transport members.
 *
 * This model represents the 'transport_members' table, linking
 * users or students to specific transport routes and vehicles.
 *
 * @property int $id
 * @property string $transportable_type
 * @property int $transportable_id
 * @property int $transport_route_id
 * @property int $transport_vehicle_id
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $note
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class TransportMember extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transportable_type',
        'transportable_id',
        'transport_route_id',
        'transport_vehicle_id',
        'start_date',
        'end_date',
        'note',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the parent transportable model (e.g., User, Student).
     *
     * @return MorphTo
     */
    public function transportable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the transport route.
     *
     * @return BelongsTo
     */
    public function transportRoute(): BelongsTo
    {
        return $this->belongsTo(TransportRoute::class);
    }

    /**
     * Get the transport vehicle.
     *
     * @return BelongsTo
     */
    public function transportVehicle(): BelongsTo
    {
        return $this->belongsTo(TransportVehicle::class);
    }

    /**
     * Get the user who created the transport member record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the transport member record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
