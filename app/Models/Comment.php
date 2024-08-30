<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends BaseModel
{
    use HasFactory;

    /**
     *
     * Comment types
     * 1 = Item
     * 2 = Game
     *
     */

    protected $fillable = [
        'user_id',
        'text',
        'target_id',
        'scrubbed',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scrub()
    {
        if($this->scrubbed)
        {
            $this->update(['scrubbed' => false]);
        } else {
            $this->update(['text' => '[Content Removed]', 'scrubbed' => true]);
        }
    }
}
