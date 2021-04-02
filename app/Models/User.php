<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    const ROLE_BOARD = 1;
    const ROLE_EXPERT = 2;
    const ROLE_TRAINER = 3;
    const ROLE_COMPETITOR = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'profile_id',
        'skill_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function skill()
    {
        return $this->belongsTo('App\Models\Skill');
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }

    public function activityParticipant() {
        return $this->hasMany('App\Models\ActivityParticipant');
    }

    /**
     * @return string
     */
    public function getProfileName () : string
    {
        $profile = [
            self::ROLE_BOARD => 'board',
            self::ROLE_EXPERT => 'expert',
            self::ROLE_TRAINER => 'trainer',
            self::ROLE_COMPETITOR => 'competitor'
        ];

        if (isset($profile[$this->profile])) {
            return $profile[$this->profile];
        }

        return '';
    }

    public static function checkIfLoginValid($username, $password)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return 'No such user';
        }

        if (!password_verify($password, $user->password)) {
            return 'Wrong password';
        }

        return $user;
    }
}
