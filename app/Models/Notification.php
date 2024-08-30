<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends BaseModel
{
    use HasFactory;

    protected $table = 'user_notifications';

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'url',
        'read',
        'sender_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function from()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }


    /*

    Notification Types:
    
        FRIENDS
        1. New Friend Request
        2. Friend Request Accepted

        INBOX
        3. New Message

        FORUM
        4. Thread Reply
        5. User Quoted

        SYSTEM
        6. Achievement Awarded
        7. Global Announcement

        GUILD
        --

    */
}
