<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentJob extends Model
{
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
