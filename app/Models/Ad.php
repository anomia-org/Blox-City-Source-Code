<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "creator_id",
        "item_id",
        "image_path",
        "bid",
        "total_bids",
        'bid_at',
    ];

    protected $hidden = [
        "creator_id",
        "pending",
        "bid", 
        "total_bids", 
        "total_clicks",
    ];

    protected $dates = [
        'bid_at',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function get_url()
    {
        return "" . route('market.item', $this->item_id);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function getReviewStatus() 
    {
        switch($this->pending) {
            case 0:
                return 'Approved';
            break;
            case 1:
                return 'Pending Review';
            break;
            case 2:
                return 'Rejected';
            break;
        }
    }

    public function isRunning() 
    {
        if($this->pending > 0) return false;
        if($this->bid_at->diffInHours(now()) > 24) {
            $this->bid = 0;
            $this->save();
            return false;
        }
        return true;
    }

    public function scopeRunning($query) 
    {
        return $query->where('pending', 0)->where('bid_at', '>', now()->subHours(24));
    }

    public function scopeFindWithAccessOrFail($query, $id)
    {
        $scope = $query->where('id', $id)->where('creator_id', auth()->user()->id);

        if($scope == null) return abort(404);
        else return $scope;
    }
}
