<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildMember extends Model
{
    use HasFactory;

    protected $table = 'guilds_members';

    protected $fillable = [
        'guild_id',
        'user_id',
        'rank'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guild()
    {
        return $this->belongsTo(Guild::class);
    }

    public function rank()
    {
        return GuildRank::where([
            ['guild_id', '=', $this->guild_id],
            ['rank', '=', $this->rank]
        ])->first();
    }
}
