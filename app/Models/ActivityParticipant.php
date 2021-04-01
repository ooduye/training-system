<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityParticipant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'activity_id',
        'user_id',
    ];

    public function activity()
    {
        return $this->belongsTo('App\Models\Activity');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
