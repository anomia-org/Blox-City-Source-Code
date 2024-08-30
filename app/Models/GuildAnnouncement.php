<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildAnnouncement extends Model
{
    use HasFactory;

    protected $table = 'guilds_announcements';

    protected $fillable = [
        'user_id',
        'guild_id',
        'title',
        'body',
        'scrubbed'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
