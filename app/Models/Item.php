<?php

namespace App\Models;

use App\Jobs\NotifyWebhooks;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'desc',
        'creator_id',
        'type',
        'source',
        'stock_limit',
        'cash',
        'coins',
        'special',
        'pending',
        'approved_by',
        'sales',
        'hash',
        'updated_real',
        'offsale_at',
    ];

    protected $dates = [
        'updated_real',
        'offsale_at',
        'created_at',
        'updated_at',
    ];
    
    public function owner()
    {
        if($this->creator_type == 1)
        {
            return $this->belongsTo(User::class, 'creator_id');
        } elseif($this->creator_type == 2) {
            return $this->belongsTo(Guild::class, 'creator_id');
        }
    }

    public function copies()
    {
        return $this->hasMany(Inventory::class, 'item_id');
    }

    public function latest_copy()
    {
        return $this->hasOne(Inventory::class, 'item_id')->latest()->first();
    }

    public function sold()
    {
        return $this->hasMany(Inventory::class, 'item_id')->latest()->count();
    }

    public function get_render()
    {
        if($this->pending == 1)
        {
            return asset('img/market/pending.png');
        } elseif($this->pending == 2) {
            return asset('img/market/denied.png');
        } else {
            return "https://cdn.bloxcity.com/".$this->hash.".png";
        }
    }

    public function stock()
    {
        if($this->special == 0)
        {
            return -1;
        }
        if($this->latest_copy() != null)
        {
            return $this->stock_limit - $this->copies()->count();
        } else {
            return $this->stock_limit;
        }
    }

    public function market()
    {
        return $this->hasMany(ItemReseller::class, 'item_id')->orderBy('price', 'ASC');
    }

    public function historicalData()
    {
        return $this->hasMany(ItemData::class, 'item_id')->orderBy('created_at', 'ASC');
    }

    public function chartLabels()
    {
        $labels = [];
        $data = $this->historicalData;
        foreach($data as $d)
        {
            $labels[] = $d->created_at->format('m/d/Y H:i');
        }
        return $labels;
    }

    public function chartData()
    {
        $data = [];
        $prices = [];
        $data = $this->historicalData;
        foreach($data as $d)
        {
            $prices[] = $d->price;
        }
        return $prices;
    }

    public function avgResalePrice()
    {
        $salesCount = DB::table('special_data')
            ->where('item_id', $this->id)
            ->count();

        if ($salesCount == 0) {
            // Return the original item price if no sales exist
            return 0; // Assuming the original price is stored in 'cash' column
        }

        // Assuming your sales table is named 'special_data' and has 'item_id' and 'price' columns
        $averageSalesPrice = DB::table('special_data')
            ->where('item_id', $this->id)
            ->avg('price');

        return (int) round($averageSalesPrice); // Return the average sales price rounded to the nearest integer
    }

    public function get_type()
    {
        $sort = $this->type;
        switch ($sort) {
            case 4:
                $type = 'Shirt';
                break;
            case 5:
                $type = 'Pants'; 
                break;
            case 1:
                $type = 'Hat';
                break;
            case 2:
                $type = 'Face';
                break;
            case 3:
                $type = 'Accessory';
                break;
            case 6:
                $type = 'T-Shirt';
                break;
            case 7:
                $type = 'Box';
                break;
        }
        return $type;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'target_id')->where('type', '=', '1');
    }

    public function free()
    {
        if($this->cash < 0 && $this->coins < 0)
        {
            return true;
        } else {
            return false;
        }
    }

    public function notifyWebhooks($isNew)
    {
        NotifyWebhooks::dispatch($this->id, $isNew);
    }

    public function get_short_price($num) {
        if ($num < 999) {
            return $num;
        }
        else if ($num > 999 && $num <= 9999) {
            $new_num = substr($num, 0, 1);
            return $new_num.'K+';
        }
        else if ($num > 9999 && $num <= 99999) {
            $new_num = substr($num, 0, 2);
            return $new_num.'K+';
        }
        else if ($num > 99999 && $num <= 999999) {
            $new_num = substr($num, 0, 3);
            return $new_num.'K+';
        }
        else if ($num > 999999 && $num <= 9999999) {
            $new_num = substr($num, 0, 1);
            return $new_num.'M+';
        }
        else if ($num > 9999999 && $num <= 99999999) {
            $new_num = substr($num, 0, 2);
            return $new_num.'M+';
        }
        else if ($num > 99999999 && $num <= 999999999) {
            $new_num = substr($num, 0, 3);
            return $new_num.'M+';
        }
        else {
            return $num;
        }
    }
}
