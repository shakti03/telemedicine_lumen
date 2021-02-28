<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Appointment extends Model
{
    const statuses = [
        'PENDING',
        'APPROVED',
        'REJECTED',
        'COMPLETED'
    ];

    protected $hidden = [
        'id', 'meeting_id', 'created_at', 'updated_at'
    ];

    public function getStatusAttribute($value)
    {
        return strtolower($value);
    }

    /**
     * Meeting and MeetingQuestion Relationship
     */
    public function questions()
    {
        return $this->hasMany(AppointmentAnswer::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($meeting) {
            $meeting->uuid = Str::uuid();
        });
    }
}
