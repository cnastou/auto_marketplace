<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransportRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'pickup_date',
        'delivery_date',
        'pickup_location',
        'delivery_location',
        'status',
        'notes',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'delivery_date' => 'date',
    ];

    // Customer who created the transport request
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Vehicle being transported
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Bids submitted by transporters
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }
}
