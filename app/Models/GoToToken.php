<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GoToToken extends Model
{
    protected $table = "goto_tokens";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'access_token', 'refresh_token', 'token_type', 'organizer_key', 'expires_in', 'other'
    ];

    protected $hidden = [
        'id', 'created_at', 'updated_at'
    ];
}
