<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Meeting;

class MeetingQuestion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meeting_id',  'title'
    ];

    public function meeting()
    {
        $this->belongsTo(Meeting::class);
    }
}
