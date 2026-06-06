<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'order_id', 'type', 'sequential', 'access_key',
        'authorization_date', 'xml_path', 'ride_path',
        'sri_status', 'customer_name', 'customer_ruc',
        'customer_email', 'customer_address', 'sri_response',
    ];

    protected function casts(): array
    {
        return [
            'authorization_date' => 'datetime',
            'sri_response' => 'json',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
