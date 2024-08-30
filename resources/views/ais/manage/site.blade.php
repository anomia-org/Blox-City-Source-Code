<x-admin-layout>
    <x-slot name="title">Site Settings</x-slot>
    @php
        $settings = \Illuminate\Support\Facades\DB::table('site_settings')->where('id', '=', '1')->first();
    @endphp
    <div class="card">
        <div class="card-header">Site Settings</div>
        <div class="card-body">
            <form action="{{ route('ais.manage.site.update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <strong>Features</strong>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="maintenance_enabled" @if (\App\Models\Setting::where('maintenance_enabled', '1')->get()->first()) checked @endif>
                            <label class="form-check-label" for="maintenance_enabled">Maintenance Enabled</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="banner_enabled" @if (\App\Models\Setting::where('banner_enabled', '1')->get()->first()) checked @endif>
                            <label class="form-check-label" for="banner_enabled">Alert Enabled</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="market_enabled" @if (\App\Models\Setting::where('market_enabled', '1')->get()->first()) checked @endif>
                            <label class="form-check-label" for="market_enabled">Market Enabled</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="forum_enabled" @if (\App\Models\Setting::where('forum_enabled', '1')->get()->first()) checked @endif>
                            <label class="form-check-label" for="forum_enabled">Forum Enabled</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="avatar_enabled" @if (\App\Models\Setting::where('avatar_enabled', '1')->get()->first()) checked @endif>
                            <label class="form-check-label" for="avatar_enabled">Avatar Editor Enabled</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="trading_enabled" @if (\App\Models\Setting::where('trading_enabled', '1')->get()->first()) checked @endif>
                            <label class="form-check-label" for="trading_enabled">Trading Enabled</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="groups_enabled" @if (\App\Models\Setting::where('groups_enabled', '1')->get()->first()) checked @endif>
                            <label class="form-check-label" for="groups_enabled">Groups Enabled</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="settings_enabled" @if (\App\Models\Setting::where('settings_enabled', '1')->get()->first()) checked @endif>
                            <label class="form-check-label" for="settings_enabled">Settings Enabled</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="register_enabled" @if (\App\Models\Setting::where('register_enabled', '1')->get()->first()) checked @endif>
                            <label class="form-check-label" for="register_enabled">Registration Enabled</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <strong>Banner Message</strong><br>
                        <textarea class="form-control mb-2" name="alert_message" placeholder="Site alert here..." rows="5">{{ $settings->banner_text }}</textarea>
                        <strong>Banner Colors</strong>
                        <div class="row">
                            <div class="col-6">
                                <label for="alert_background_color">Background</label><br>
                                <input class="form-control mb-2" type="text" name="alert_background_color" placeholder="Alert Background Color" value="{{ $settings->banner_color }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="green w-100 mt-1" type="submit">Update</button>
            </form>
        </div>
    </div>
</x-admin-layout>