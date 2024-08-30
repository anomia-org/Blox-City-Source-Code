<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntelopeLog extends Model
{
    use HasFactory;

    protected $table = 'antelope_logs';

    protected $fillable = [
        'user_id',
        'action'
    ];

}
