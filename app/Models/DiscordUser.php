<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscordUser extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id',
        'username',
        'discriminator',
        'avatar',
        'verified',
        'locale',
        'mfa_enabled',
        'refresh_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'refresh_token',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'string',
        'id' => 'string',
        'username' => 'string',
        'discriminator' => 'string',
        'avatar' => 'string',
        'verified' => 'boolean',
        'locale' => 'string',
        'mfa_enabled' => 'boolean',
        'refresh_token' => 'encrypted',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
