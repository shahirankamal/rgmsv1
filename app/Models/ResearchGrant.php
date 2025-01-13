<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchGrant extends Model
{
    protected $fillable = [
        'title',
        'grant_amount',
        'grant_provider',
        'start_date',
        'duration',
        'leader_id'
    ];

    public function leader()
    {
        return $this->belongsTo(Academician::class, 'leader_id');
    }

    public function members()
    {
        return $this->belongsToMany(Academician::class, 'grant_members', 'grant_id', 'academician_id');
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'grant_id');
    }
}
