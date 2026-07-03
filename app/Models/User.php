<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the contact inquiries assigned to the user.
     */
    public function contacts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Contact::class, 'assigned_to');
    }

    /**
     * Get the replies sent by the user.
     */
    public function contactReplies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ContactReply::class, 'user_id');
    }

    /**
     * Get the internal notes written by the user.
     */
    public function contactNotes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ContactNote::class, 'user_id');
    }
}
