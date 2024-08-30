<?php

use App\Models\Setting;

function settings($key)
{
    $settings = Setting::where('id', '=', 1)->first();
    return $settings->{$key};
}

?>