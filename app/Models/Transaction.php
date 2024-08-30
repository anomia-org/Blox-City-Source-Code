<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends BaseModel
{
    use HasFactory;

    protected $table = 'user_transactions';

    protected $fillable = [
        'user_id',
        'source_id',
        'source_user',
        'source_type',
        'type',
        'cash',
        'coins',
        'release_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function source()
    {
        if($this->source_type == 1)
        {
            return $this->belongsTo(Item::class, 'source_id');
        } elseif($this->source_type == 2) {
            return $this->belongsTo(Guild::class, 'source_id');
        } elseif($this->source_type == 3) {
            //return $this->belongsTo(World::class, 'source_id');
        } elseif($this->source_type == 4) {
            return $this->belongsTo(User::class, 'source_id');
        }
    }

    public function url()
    {
        if($this->source_type == 1)
        {
            return route('market.item', $this->source_id);
        } elseif($this->source_type == 2) {
            return route('groups.view', $this->source_id);
        } elseif($this->source_type == 3) {
            //return route('worlds.view', $this->source_id);
        }
    }

    public function image()
    {
        if($this->source_type == 1)
        {
            //return route('market.item', $this->source_id);
            return 'https://cdn.bloxcity.com/' . $this->source->hash . '.png';
        } elseif($this->source_type == 2) {
            //return route('groups.view', $this->source_id);
            return '';
        } elseif($this->source_type == 3) {
            //return route('worlds.view', $this->source_id);
            return '';
        }
    }

    public function get_type()
    {
        if($this->type == 1)
        {
            return 'Purchase';
        } elseif($this->type == 2) {
            return 'Sale';
        } elseif($this->type == 3) {
            return 'Guild Payout';
        } elseif($this->type == 4) {
            return 'Daily Stipend';
        } elseif($this->type == 5) {
            return 'Currency Purchase';
        } elseif($this->type == 6) {
            return 'Guild Creation';
        }
    }

    public function get_member()
    {
        return $this->belongsTo(User::class, 'source_user');
    }

    /**
     *
     * Transaction types
     * 1 = Purchase
     * 2 = Sale
     * 3 = Guild Payout
     * 4 = Membership Stipend
     * 5 = Currency Purchases
     * 6 = Guild Creation
     *
     * Source types
     * 1 = Item
     * 2 = Guild
     * 3 = World
     * 4 = System
     *
     */
}
