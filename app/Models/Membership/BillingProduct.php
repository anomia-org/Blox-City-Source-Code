<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\{
    Model,
    Builder,
};

class BillingProduct extends Model
{
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('power', function (Builder $builder) {
            $builder->where('active', 1);
        });
    }
}
