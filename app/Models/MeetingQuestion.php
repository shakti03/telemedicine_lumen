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

    protected $hidden = [
        'created_at', 'updated_at', 'meeting_id'
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
