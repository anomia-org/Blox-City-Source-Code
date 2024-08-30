<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Blurb extends BaseModel
{
    use HasFactory;

    protected $table = 'user_feed';

    protected $fillable = [
        'author_id',
        'author_type',
        'text',
        'scrubbed',
    ];
    
    /*
    * Author types:
    * 1 = User
    * 2 = Guild
    */

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function owner()
    {
        if($this->author_type == 1)
        {
            return $this->belongsTo(User::class, 'author_id');
        } elseif($this->author_type == 2) {
            return $this->belongsTo(Guild::class, 'author_id');
        }
    }
}
