<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ip extends BaseModel
{
    use HasFactory;

    protected $table = 'ips';

    protected $fillable = [
        'ip',
        'user_id',
    ];

    protected $dates = [
        'last_used',
    ];
}
