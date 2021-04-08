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

    const PAYMENT_PENDING = "PENDING";
    const PAYMENT_FAILED = "FAILED";
    const PAYMENT_PAID = "PAID";

    protected $hidden = [
        'id', 'meeting_id', 'created_at', 'updated_at'
    ];

    public function getStatusAttribute($value)
    {
        if ($this->payment_status == self::PAYMENT_PAID) {
            return "paid";
        } elseif ($this->payment_status == self::PAYMENT_FAILED) {
            return 'not paid';
        }

        return strtolower($value);
    }

    /**
     * Meeting and MeetingQuestion Relationship
     */
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Meeting and MeetingQuestion Relationship
     */
    public function gotomeeting()
    {
        return $this->hasOne(GoToMeeting::class);
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
