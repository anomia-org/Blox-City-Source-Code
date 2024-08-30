<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileView extends BaseModel
{
    use HasFactory;

    protected $table = 'profile_views';

    protected $fillable = [
        'user_id',
        'target_id',
        'ip',
    ];
}
