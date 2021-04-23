<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AppointmentStat extends Model
{
    protected $fillable = [
        'stat_month', 'stat_year', 'stat_date', 'old_patients_count', 'new_patients_count', 'user_id'
    ];
}
