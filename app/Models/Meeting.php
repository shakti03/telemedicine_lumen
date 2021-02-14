<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MeetingSchedules;

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

    /**
     * Meeting and MeetingSchedule Relationship
     */
    public function schedules()
    {
        $this->hasMany(MeetingSchedule::class);
    }

    /**
     * Meeting and MeetingQuestion Relationship
     */
    public function questions()
    {
        $this->hasMany(MeetingQuestion::class);
    }
}
