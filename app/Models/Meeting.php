<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Meeting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'location', 'description'
    ];

    protected $hidden = [
        'id', 'created_at', 'updated_at', 'user_id'
    ];

    /**
     * Meeting and User Relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Meeting and MeetingSchedule Relationship
     */
    public function schedules()
    {
        return $this->hasMany(MeetingSchedule::class);
    }

    /**
     * Meeting and MeetingQuestion Relationship
     */
    public function questions()
    {
        return $this->hasMany(MeetingQuestion::class);
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
