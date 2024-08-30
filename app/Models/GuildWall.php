<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildWall extends Model
{
    use HasFactory;

    protected $table = 'guild_walls';

    protected $fillable = [
        'user_id',
        'guild_id',
        'text',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
