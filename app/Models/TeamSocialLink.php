<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamSocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_member_id',
        'platform',
        'url',
        'display_order',
    ];

    protected $casts = [
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
