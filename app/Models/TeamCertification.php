<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamCertification extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_member_id',
        'title',
        'organization',
        'issue_date',
        'certificate_file_id',
        'display_order',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'display_order' => 'integer',
    ];

    public function member()
    {
        return $this->belongsTo(TeamMember::class, 'team_member_id');
    }

    public function certificateFile()
    {
        return $this->belongsTo(Media::class, 'certificate_file_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderByDesc('created_at');
    }
}
