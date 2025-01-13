<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Academician extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'staff_number', 'email', 'college', 'department', 'position'];

    public function researchGrants()
    {
        return $this->hasMany(ResearchGrant::class, 'leader_id');
    }

    public function ledGrants()
    {
        return $this->hasMany(ResearchGrant::class, 'leader_id');
    }

    public function memberGrants()
    {
        return $this->belongsToMany(ResearchGrant::class, 'grant_members', 'academician_id', 'grant_id');
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
