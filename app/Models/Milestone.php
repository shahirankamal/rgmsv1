<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'milestone_name',
        'target_completion_date',
        'deliverable',
        'status',
        'grant_id',
        'completion_date',
        'remark'
    ];

    public function grant()
    {
        return $this->belongsTo(ResearchGrant::class, 'grant_id');
    }
}
