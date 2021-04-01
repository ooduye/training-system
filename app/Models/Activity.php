<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'skill_id',
        'title',
        'description',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function skill()
    {
        return $this->belongsTo('App\Models\Skill');
    }

    public function activityParticipants() {
        return $this->hasMany('App\Models\ActivityParticipant');
    }

    public function participants()
    {
        return $this->belongsToMany('App\Models\User', 'activity_participants');
    }
}
