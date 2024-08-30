<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemData extends Model
{
    use HasFactory;

    protected $table = 'special_data';

    protected $fillable = [
        'item_id',
        'price',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
