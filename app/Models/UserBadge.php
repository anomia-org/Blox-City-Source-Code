<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBadge extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'granter_id',
        'badge_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function granter()
    {
        return $this->belongsTo(User::class, 'granter_id');
    }
}
