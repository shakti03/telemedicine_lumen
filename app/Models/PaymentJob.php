<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentJob extends Model
{
    const STATUS_PENDING = "PENDING";
    const STATUS_COMPLETED = "COMPLETED";
    const STATUS_FAILED = "FAILED";

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
