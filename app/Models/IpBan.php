<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpBan extends BaseModel
{
    use HasFactory;

    protected $table = 'ip_bans';
}
