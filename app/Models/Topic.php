<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Topic extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $cacheCooldownSeconds = 300; // 5 minutes
    protected $guarded = [];
    protected $softDelete = true;

    public function threads()
    {
        if(Auth::check() && Auth::user()->power > 0)
        {
            return $this->hasMany(Thread::class);
        } else {
            return $this->hasMany(Thread::class)->where('deleted', '=', '0');
        }
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function latestThread()
    {
        return $this->hasOne(Thread::class)->where('deleted', '=', '0')->latest();
    }

    public function latestReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }
}
