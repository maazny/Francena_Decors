<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_member_id',
        'skill_name',
        'skill_percentage',
        'display_order',
    ];

    protected $casts = [
        'skill_percentage' => 'integer',
        'display_order' => 'integer',
    ];

    public function member()
    {
        return $this->belongsTo(TeamMember::class, 'team_member_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderByDesc('created_at');
    }
}
