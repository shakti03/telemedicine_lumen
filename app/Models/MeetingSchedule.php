<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MeetingSchedule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meeting_id', 'date', 'start_time', 'end_time'
    ];

    public function meeting()
    {
        $this->belongsTo(Meeting::class);
    }
}
