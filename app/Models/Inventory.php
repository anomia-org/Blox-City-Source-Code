<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'type',
        'collection_number',
        'special',
        'can_trade',
        'can_open',
        'crate_id',
    ];

    public function onsale()
    {
        return ItemReseller::where('inventory_id', '=', $this->id)->exists();
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getSerialNumber()
    {
        // Fetch all inventory items of the same type, ordered by created_at
        $inventoryItems = self::where('item_id', $this->item_id)
            ->orderBy('created_at')
            ->get();

        // Find the index of the current item in the ordered list
        foreach ($inventoryItems as $index => $inventoryItem) {
            if ($inventoryItem->id == $this->id) {
                // Serial number is index + 1 (because index is 0-based)
                return $index + 1;
            }
        }

        // If the item is not found in the list, return 0 or any other appropriate value
        return 0;
    }
}
