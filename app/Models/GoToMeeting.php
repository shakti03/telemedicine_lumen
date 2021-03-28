<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GoToMeeting extends Model
{
    protected $table = "goto_meetings";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_id', 'goto_meetingid', 'join_url', 'other'
    ];

    protected $hidden = [
        'id', 'created_at', 'updated_at', 'user_id'
    ];
}
