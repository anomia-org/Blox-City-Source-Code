<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reply extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $softDelete = true;

    protected $dates = [
        'created_at',
        'edited_at',
        'deleted_at'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function likes()
    {
        return $this->hasMany(ForumLike::class, 'target_id')->where('target_type', '=', '2');
    }

    public function scrub()
    {
        if($this->scrubbed)
        {
            $this->update(['scrubbed' => false]);
        } else {
            $this->update(['body' => '[Content Removed]', 'scrubbed' => true]);
        }
    }


}
