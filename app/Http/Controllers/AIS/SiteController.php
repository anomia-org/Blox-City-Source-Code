<?php

namespace App\Http\Controllers\AIS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\AntelopeLog;

class SiteController extends Controller
{
    public function index()
    {
        if (Auth::user()->power < 4) {
            abort(404);
        }

        return view('ais.manage.site');
    }

    public function update(Request $request)
    {
        $site = Setting::where('id', '=', 1)->first();
        $columns = array_keys($site->getAttributes());
        $settings = $request->except('_token', 'alert_message', 'alert_background_color');

        unset($columns[0]);
        unset($columns[3]);

        try {
            foreach ($columns as $column)
                $site->$column = false;

            foreach ($settings as $name => $value)
                $site->$name = true;

            $site->banner_text = $request->alert_message;
            $site->banner_color = $request->alert_background_color;
            $site->save();

        } catch (Exception $e) {
            return back()->withErrors(['Something went wrong.']);
        }

        return redirect()->route('ais.manage.site')->with('success_message', 'Site settings have been updated.');
    }
}
