<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreadView extends BaseModel
{
    use HasFactory;

    protected $table = 'thread_views';

    protected $fillable = [
        'user_id',
        'thread_id',
        'ip',
    ];
}
