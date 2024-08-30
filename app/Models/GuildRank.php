<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildRank extends Model
{
    use HasFactory;

    protected $table = 'guilds_ranks';

    protected $fillable = [
        'guild_id',
        'name',
        'rank'
    ];

    public function memberCount()
    {
        return number_format(GuildMember::where([
            ['guild_id', '=', $this->guild_id],
            ['rank', '=', $this->rank]
        ])->count());
    }
}
