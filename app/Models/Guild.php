<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guild extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'desc',
        'cash',
        'coins',
        'thumbnail_url',
        'is_thumbnail_pending',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function thumbnail()
    {
        if($this->is_thumbnail_pending == 1)
        {
            return asset('img/market/pending.png');
        } elseif($this->is_thumbnail_pending == 2) {
            return asset('img/market/denied.png');
        } else {
            return "https://cdn.bloxcity.com/".$this->thumbnail_url;
        }
    }

    public function raw_thumbnail()
    {
        return "https://cdn.bloxcity.com/".$this->thumbnail_url;
    }

    public function members()
    {
        return GuildMember::where('guild_id', '=', $this->id)->get();
    }

    public function ranks()
    {
        return GuildRank::where('guild_id', '=', $this->id)->orderBy('rank', 'ASC')->get();
    }

    public function announcement()
    {
        return GuildAnnouncement::where('guild_id', '=', $this->id)->latest()->first();
    }

    public function hasAnnouncement()
    {
        return GuildAnnouncement::where([
            ['guild_id', '=', $this->id]
        ])->exists();
    }

    public function get_short_num($num) {
        if ($num < 999) {
            return $num;
        }
        else if ($num > 999 && $num <= 9999) {
            $new_num = substr($num, 0, 1);
            return $new_num.'K+';
        }
        else if ($num > 9999 && $num <= 99999) {
            $new_num = substr($num, 0, 2);
            return $new_num.'K+';
        }
        else if ($num > 99999 && $num <= 999999) {
            $new_num = substr($num, 0, 3);
            return $new_num.'K+';
        }
        else if ($num > 999999 && $num <= 9999999) {
            $new_num = substr($num, 0, 1);
            return $new_num.'M+';
        }
        else if ($num > 9999999 && $num <= 99999999) {
            $new_num = substr($num, 0, 2);
            return $new_num.'M+';
        }
        else if ($num > 99999999 && $num <= 999999999) {
            $new_num = substr($num, 0, 3);
            return $new_num.'M+';
        }
        else {
            return $num;
        }
    }
}
