<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

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
        static::creating(function ($appointment) {
            $appointment->uuid = Str::uuid();
        });

        static::created(function ($appointment) {
            // Create Patient Entry
            $patient = Patient::firstOrNew([
                'email' => $appointment->patient_email
            ]);

            $patient->name = $appointment->patient_name;
            if (!$appointment->phone)
                $patient->phone = $appointment->phone;

            $patient->save();

            // Insert in appointment_stats
            $date = Carbon::parse($appointment->appointment_date);
            $stat = AppointmentStat::firstOrNew([
                'user_id'   => $appointment->meeting->user_id,
                'stat_month' => $date->month,
                'stat_year' => $date->year,
                'stat_date' => $date->format('Y-m-t')
            ]);

            $patDate = Carbon::parse($patient->created_at);
            if ($patDate->month == $date->month && $patDate->year == $date->year) {
                $stat->old_patients_count = $stat->old_patients_count ? $stat->old_patients_count + 1 : 1;
            } else {
                $stat->new_patients_count = $stat->new_patients_count ? $stat->new_patients_count + 1 : 1;
            }
            $stat->save();
        });
    }
}
