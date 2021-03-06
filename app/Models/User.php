<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordInterface;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordInterface
{
    use Authenticatable, Authorizable, HasFactory, CanResetPasswordTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    protected $appends = [
        'room_link'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get Full name attribute
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get Room Link
     */
    public function getRoomLinkAttribute()
    {
        return URL::to('p/' . $this->room_name);
    }

    /**
     * User and Meeting Relationship
     */
    public function meeting()
    {
        return $this->hasOne(Meeting::class);
    }

    /**
     * Meeting and Appointment Relationship
     */
    public function appointmentStats()
    {
        return $this->hasMany(AppointmentStat::class);
    }


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($user) {
            $meeting = new Meeting;
            $meeting->user_id = $user->id;;
            $meeting->title = "Unknown";
            $meeting->meeting_duration = 30;
            $meeting->save();
        });

        static::creating(function ($user) {
            $user->uuid =  Str::uuid();
            $user->verify_token =  Str::uuid();
        });
    }

    public function generateToken()
    {
        if (!$this->api_token) {
            $this->api_token = Str::random(40);
            $this->save();
        }

        return $this->api_token;
    }
}
