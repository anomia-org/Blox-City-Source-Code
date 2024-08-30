<x-admin-layout>
    <x-slot name="title">Site Settings</x-slot>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-md-4 col-lg-2">
                    <h4>Maintenance Enabled</h4>
                    <form action="{{ route('ais.manage.site.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="setting" value="maintenance">
                        <select class="form-control" name="enabled" required>
                            <option value="1" @if (\App\Models\Setting::where('maintenance_enabled', '1')->get()->first()) selected @endif>Yes</option>
                            <option value="0" @if (\App\Models\Setting::where('maintenance_enabled', '0')->get()->first()) selected @endif>No</option>
                        </select>
                        <button class="btn btn-success" type="submit">Update</button>
                    </form>
                </div>
                <div class="col-md-4 col-lg-2">
                    <h4>Banner Enabled</h4>
                    <form action="{{ route('ais.manage.site.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="setting" value="alert">
                        <select class="form-control" name="enabled" required>
                            <option value="1" @if (\App\Models\Setting::where('banner_enabled', '1')->get()->first()) selected @endif>Yes</option>
                            <option value="0" @if (\App\Models\Setting::where('banner_enabled', '0')->get()->first()) selected @endif>No</option>
                        </select>
                        <button class="btn btn-success" type="submit">Update</button>
                    </form>
                </div>
                <div class="col-md-4 col-lg-2">
                    <h4>Upgrades Enabled</h4>
                    <form action="{{ route('ais.manage.site.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="setting" value="upgrades">
                        <select class="form-control" name="enabled" required>
                            <option value="1" @if (\App\Models\Setting::where('upgrades_enabled', '1')->get()->first()) selected @endif>Yes</option>
                            <option value="0" @if (\App\Models\Setting::where('upgrades_enabled', '0')->get()->first()) selected @endif>No</option>
                        </select>
                        <button class="btn btn-success" type="submit">Update</button>
                    </form>
                </div>
                <div class="col-md-4 col-lg-2">
                    <h4>Market Enabled</h4>
                    <form action="{{ route('ais.manage.site.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="setting" value="market_purchases">
                        <select class="form-control" name="enabled" required>
                            <option value="1" @if (\App\Models\Setting::where('market_enabled', '1')->get()->first()) selected @endif>Yes</option>
                            <option value="0" @if (\App\Models\Setting::where('market_enabled', '0')->get()->first()) selected @endif>No</option>
                        </select>
                        <button class="btn btn-success" type="submit">Update</button>
                    </form>
                </div>
                <div class="col-md-4 col-lg-2">
                    <h4>Forum Enabled</h4>
                    <form action="{{ route('ais.manage.site.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="setting" value="forum">
                        <select class="form-control" name="enabled" required>
                            <option value="1" @if (\App\Models\Setting::where('forum_enabled', '1')->get()->first()) selected @endif>Yes</option>
                            <option value="0" @if (\App\Models\Setting::where('forum_enabled', '0')->get()->first()) selected @endif>No</option>
                        </select>
                        <button class="btn btn-success" type="submit">Update</button>
                    </form>
                </div>
                <div class="col-md-4 col-lg-2">
                    <h4>Creator Area Enabled</h4>
                    <form action="{{ route('ais.manage.site.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="setting" value="creator_area">
                        <select class="form-control" name="enabled" required>
                            <option value="1" @if (\App\Models\Setting::where('creator_area_enabled', '1')->get()->first()) selected @endif>Yes</option>
                            <option value="0" @if (\App\Models\Setting::where('creator_area_enabled', '0')->get()->first()) selected @endif>No</option>
                        </select>
                        <button class="btn btn-success" type="submit">Update</button>
                    </form>
                    <br>
                </div>
                <div class="col-lg-3"></div>
                <div class="col-md-4 col-lg-2">
                    <h4>Character Editing Enabled</h4>
                    <form action="{{ route('ais.manage.site.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="setting" value="character">
                        <select class="form-control" name="enabled" required>
                            <option value="1" @if (\App\Models\Setting::where('avatar_enabled', '1')->get()->first()) selected @endif>Yes</option>
                            <option value="0" @if (\App\Models\Setting::where('avatar_enabled', '0')->get()->first()) selected @endif>No</option>
                        </select>
                        <button class="btn btn-success" type="submit">Update</button>
                    </form>
                </div>
                <div class="col-md-4 col-lg-2">
                    <h4>Settings Enabled</h4>
                    <form action="{{ route('ais.manage.site.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="setting" value="settings">
                        <select class="form-control" name="enabled" required>
                            <option value="1" @if (\App\Models\Setting::where('settings_enabled', '1')->get()->first()) selected @endif>Yes</option>
                            <option value="0" @if (\App\Models\Setting::where('settings_enabled', '0')->get()->first()) selected @endif>No</option>
                        </select>
                        <button class="btn btn-success" type="submit">Update</button>
                    </form>
                </div>
                <div class="col-md-4 col-lg-2">
                    <h4>Registration Enabled</h4>
                    <form action="{{ route('ais.manage.site.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="setting" value="registration">
                        <select class="form-control" name="enabled" required>
                            <option value="1" @if (\App\Models\Setting::where('register_enabled', '1')->get()->first()) selected @endif>Yes</option>
                            <option value="0" @if (\App\Models\Setting::where('register_enabled', '0')->get()->first()) selected @endif>No</option>
                        </select>
                        <button class="btn btn-success" type="submit">Update</button>
                    </form>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-md-12 col-lg-6">
                    <h4>Banner Message</h4>
                    <form action="{{ route('ais.manage.site.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="setting" value="alert_message">
                        <textarea class="form-control" name="message" rows="5">{{ settings('alert_message') }}</textarea>
                        <button class="btn btn-success" type="submit">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>