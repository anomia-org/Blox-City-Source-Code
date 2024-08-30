<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        "to",
        "subject",
        "body"
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, "from");
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, "to");
    }

    public function main()
    {
        return $this->belongsTo(Message::class, "reply_to");
    }

    public function hasAccess() {
        return $this->to == auth()->user()->id || $this->from == auth()->user()->id;
    }

}
