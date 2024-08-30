<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at'];

    protected $softDelete = true;

    protected $table = "reports";

    public function owner()
    {
        return $this->belongsTo(User::class, 'by');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    public function content()
    {
        switch($this->type)
        {
            case 1:
                return $this->belongsTo(Thread::class, 'rid');
                break;
            case 2:
                return $this->belongsTo(Reply::class, 'rid');
                break;
            case 3:
                return $this->belongsTo(User::class, 'rid');
                break;
            case 4:
                return $this->belongsTo(Blurb::class, 'rid');
                break;
            case 5:
                return $this->belongsTo(Item::class, 'rid');
                break;
            case 6:
                return $this->belongsTo(Comment::class, 'rid');
                break;
            case 7:
                return $this->belongsTo(Message::class, 'rid');
                break;
            case 8:
                return $this->belongsTo(Guild::class, 'rid');
                break;
            case 9:
                return $this->belongsTo(GuildWall::class, 'rid');
                break;
            case 10:
                return $this->belongsTo(GuildAnnouncement::class, 'rid');
                break;
            case 11:
                return $this->belongsTo(Ad::class, 'rid');
                break;
        }
    }

    public function linkify()
    {
        switch($this->type)
        {
            case 1:
                return route('forum.thread', $this->rid);
                break;
            case 2:
                return '#';
                break;
            case 3:
                return route('user.profile', $this->rid);
                break;
            case 4:
                return '#';
                break;
            case 5:
                return route('market.item', $this->rid);
                break;
            case 6:
                return '#';
                break;
            case 7:
                return '#';
                break;
            case 8:
                return route('groups.view', $this->rid);
                break;
            case 9:
                return '#';
                break;
            case 10:
                return '#';
                break;
            case 11:
                return '#';
                break;
        }
    }

    public function type()
    {
        $type = "";
        switch($this->type)
        {
            case 1:
                $type = "Forum Thread";
                break;
            case 2:
                $type = "Forum Reply";
                break;
            case 3:
                $type = "User";
                break;
            case 4:
                $type = "Blurb";
                break;
            case 5:
                $type = "Item";
                break;
            case 6:
                $type = "Comment";
                break;
            case 7:
                $type = "Message";
                break;
            case 8:
                $type = "Guild";
                break;
            case 9:
                $type = "Guild Wall Post";
                break;
            case 10:
                $type = "Guild Announcement";
                break;
            case 11:
                $type = "Advertisement";
                break;
        }
        return $type;
    }

    /**
     *
     * Report types
     * 1 = Thread
     * 2 = Reply
     * 3 = User
     * 4 = Blurb
     * 5 = Item
     * 6 = Comment
     * 7 = Message
     * 8 = Guild
     * 9 = Guild Wall
     * 10 = Guild Announcement
     * 11 = Advertisement
     *
     */
}
