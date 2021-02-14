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

    protected $hidden = [
        'created_at', 'updated_at', 'meeting_id'
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
