<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemReseller extends Model
{
    use HasFactory;

    protected $table = 'special_market';

    protected $fillable = [
        'user_id',
        'item_id',
        'inventory_id',
        'price',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }
}
