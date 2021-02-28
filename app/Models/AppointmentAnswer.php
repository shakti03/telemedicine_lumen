<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentAnswer extends Model
{
    protected $hidden = [
        'id', 'appointment_id', 'created_at', 'updated_at'
    ];
}
