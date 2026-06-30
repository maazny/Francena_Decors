<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceProcess extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'step_number', 'title', 'description', 'icon', 'display_order', 'status'];

    protected $casts = ['step_number' => 'integer', 'display_order' => 'integer', 'status' => 'boolean'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order')->orderBy('step_number');
    }
}
