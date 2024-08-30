<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privacy extends BaseModel
{
    use HasFactory;

    protected $table = 'user_privacy';

    protected $fillable = [
        'user_id',
        'message',
        'inventory',
        'fillable',
        'trade',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
