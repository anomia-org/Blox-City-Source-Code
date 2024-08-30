<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'tshirt_id',
        'shirt_id',
        'pants_id',
        'hat1_id',
        'hat2_id',
        'hat3_id',
        'face_id',
        'tool_id',
        'hex_head',
        'hex_torso',
        'hex_larm',
        'hex_rarm',
        'hex_lleg',
        'hex_rleg',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hat1()
    {
        return Item::where('id', '=', $this->hat1_id)->first();
    }

    public function hat2()
    {
        return Item::where('id', '=', $this->hat2_id)->first();
    }

    public function hat3()
    {
        return Item::where('id', '=', $this->hat3_id)->first();
    }

    public function shirt()
    {
        return Item::where('id', '=', $this->shirt_id)->first();
    }

    public function tshirt()
    {
        return Item::where('id', '=', $this->tshirt_id)->first();
    }

    public function pants()
    {
        return Item::where('id', '=', $this->pants_id)->first();
    }

    public function tool()
    {
        return Item::where('id', '=', $this->tool_id)->first();
    }

    public function face()
    {
        return Item::where('id', '=', $this->face_id)->first();
    }
}