@if(\Illuminate\Support\Facades\DB::table('site_settings')->where('banner_enabled', '=', '1')->exists())
    @php
        $settings = \Illuminate\Support\Facades\DB::table('site_settings')->where('banner_enabled', '=', '1')->first();
    @endphp
    <div class="site-banner {{ $settings->banner_color }}">
        {!! $settings->banner_text !!}
    </div>
@endif