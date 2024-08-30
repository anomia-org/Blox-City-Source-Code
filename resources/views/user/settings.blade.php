<?php
function obfuscate_email($email)
{
    $em   = explode("@", $email);
    $name = implode('@', array_slice($em, 0, count($em) - 1));
    $len  = floor(strlen($name) / 2);

    return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
}
?>

<x-app-layout>
    <x-slot name="title">Account Settings</x-slot>
    <x-slot name="navigation"></x-slot>
    <body class="settings-page">
        <x-slot name="script">
            <script>
                var currentTab = 'account';

                $(function() {
                    $('.tab-link').click(function(tab) {
                        $(`#${currentTab}_tab`).removeClass('active');
                        $(`#${tab.target.id}`).addClass('active');

                        $(`#${currentTab}`).hide();

                        currentTab = tab.target.id.replace('_tab', '');

                        $(`#${currentTab}`).show();
                    });
                });
            </script>
        </x-slot>
        @if (App\Models\Setting::where('settings_enabled', '0')->get()->first())
            <div class="container construction-container">
                <i class="icon icon-sad construction-icon"></i>
                <div class="construction-text">Sorry, Account Settings are unavailable right now for maintenance. Try again later.</div>
            </div>
        @else
            <div class="tabs">
                <div class="tab">
                    <a class="tab-link active" id="account_tab">Account</a>
                </div>
                <div class="tab">
                    <a class="tab-link" id="privacy_tab">Privacy & Blocked</a>
                </div>
                <div class="tab">
                    <a class="tab-link" id="security_tab">Security</a>
                </div>
                <div class="tab">
                    <a class="tab-link" id="billing_tab">Billing</a>
                </div>
                <div class="tab">
                    <a class="tab-link" id="connections_tab">Connections</a>
                </div>
            </div>
            <div class="container" id="account">
                <div class="settings-title">Account</div>
                @if(auth()->user()->email_verified_at == NULL)
                    <form method="POST" id="verify_form" name="verify_form" action="{{ route('verification.send') }}" class="d-none">
                        @csrf
                    </form>
                @endif
                <form action="{{ route('user.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="setting" value="account">
                    <div class="setting">
                        <div class="setting-name">User ID</div>
                        <div class="setting-result">{{ number_format(auth()->user()->id) }}</div>
                    </div>
                    <div class="setting">
                        <div class="setting-name">Username</div>
                        <div class="setting-result">
                            <input class="form-input" type="text" name="username" placeholder="Username" value="{{ auth()->user()->username }}">
                        </div>
                        <div class="setting-description">Changing your username costs <span class="text-cash">$250 Cash</span>.</div>
                    </div>
                    <div class="setting">
                        <div class="setting-name">Email</div>
                        <div class="setting-result">
                            <input class="form-input" type="email" name="email" placeholder="Email" value="{{ obfuscate_email(auth()->user()->email) }}" disabled>
                        </div>
                        <div class="setting-description">
                            @if(auth()->user()->email_verified_at == NULL) <span style="color:red;"><i class="icon icon-cancel"></i> Not Verified <a href="#" onclick="event.preventDefault();document.getElementById('verify_form').submit();">Resend?</a></span> @else <span class="text-green text-sm"><i class="icon icon-verified"></i> Verified</span> @endif
                        </div>
                    </div>
                    <div class="setting">
                        <div class="setting-name">Birthday</div>
                        <div class="setting-result">
                            <input type="date" name="birthday" class="form-input" value="{{ auth()->user()->birthday }}">
                        </div>
                    </div>
                    <div class="setting">
                        <div class="setting-name">Theme</div>
                        <div class="setting-result">
                            <select class="form-input" name="theme">
                                <option value="1" @if (auth()->user()->theme == '1') selected @endif>Light</option>
                                <option value="2" @if (auth()->user()->theme == '2') selected @endif>Dark</option>
                            </select>
                        </div>
                    </div>
                    <div class="push-15"></div>
                    <div class="settings-title">Biography <div class="settings-title-extra">(1,000 characters maximum)</div></div>
                    <textarea class="form-input" name="description" placeholder="Biography" rows="5" length="100" style="white-space: pre-line">{{ auth()->user()->biography }}</textarea>
                    <div class="push-15"></div>
                    <div class="settings-title">Forum Signature <div class="settings-title-extra">(100 characters maximum)</div></div>
                    <input class="form-input" name="signature" placeholder="Signature" length="100" value="{{ auth()->user()->signature }}">
                    <div class="text-right">
                        <button class="button settings-button button-blue" type="submit">Update Account</button>
                    </div>
                </form>
            </div>
            <div class="container" id="privacy" style="display:none;">
                <div class="settings-title">Privacy</div>
                <form action="{{ route('user.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="setting" value="privacy">
                    <div class="setting">
                        <div class="setting-name">Who can message me?</div>
                        <div class="setting-result">
                            <select class="form-input" name="message">
                                <option value="1" @if(auth()->user()->privacy->message == 1)selected="selected"@endif>Everyone</option>
                                <option value="2" @if(auth()->user()->privacy->message == 2)selected="selected"@endif>Friends</option>
                                <option value="3" @if(auth()->user()->privacy->message == 3)selected="selected"@endif>Nobody</option>
                            </select>
                        </div>
                    </div>
                    <div class="setting">
                        <div class="setting-name">Who can see my current status?</div>
                        <div class="setting-result">
                            <select class="form-input" name="blurb">
                                <option value="1" @if(auth()->user()->privacy->blurb == 1)selected="selected"@endif>Everyone</option>
                                <option value="2" @if(auth()->user()->privacy->blurb == 2)selected="selected"@endif>Friends</option>
                            </select>
                        </div>
                    </div>
                    <div class="setting">
                        <div class="setting-name">Who can send me trades?</div>
                        <div class="setting-result">
                            <select class="form-input" name="trade">
                                <option value="1" @if(auth()->user()->privacy->trade == 1)selected="selected"@endif>Everyone</option>
                                <option value="2" @if(auth()->user()->privacy->trade == 2)selected="selected"@endif>Friends</option>
                                <option value="3" @if(auth()->user()->privacy->trade == 3)selected="selected"@endif>Nobody</option>
                            </select>
                        </div>
                    </div>
                    <div class="setting">
                        <div class="setting-name">Who can view my items?</div>
                        <div class="setting-result">
                            <select class="form-input" name="inventory">
                                <option value="1" @if(auth()->user()->privacy->inventory == 1)selected="selected"@endif>Everyone</option>
                                <option value="2" @if(auth()->user()->privacy->inventory == 2)selected="selected"@endif>Friends</option>
                                <option value="3" @if(auth()->user()->privacy->inventory == 3)selected="selected"@endif>Nobody</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-right">
                        <button class="button settings-button button-blue" type="submit">Update Privacy</button>
                    </div>
                </form>
                <div class="push-25"></div>
                <div class="settings-title">Blocked Users</div>
                <p>Not specified.</p>
            </div>
            <div class="container" id="security" style="display:none;">
                <div class="settings-title">Password</div>
                <form action="{{ route('user.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="setting" value="password">
                    <div class="setting">
                        <div class="setting-name">Current Password</div>
                        <div class="setting-result">
                            <input class="form-input" type="password" name="current_password" placeholder="Current Password" required>
                        </div>
                    </div>
                    <div class="setting">
                        <div class="setting-name">New Password</div>
                        <div class="setting-result">
                            <input class="form-input" type="password" name="new_password" placeholder="New Password" required>
                        </div>
                    </div>
                    <div class="setting">
                        <div class="setting-name">New Password (Again)</div>
                        <div class="setting-result">
                            <input class="form-input" type="password" name="new_password_confirmation" placeholder="New Password (Again)" required>
                        </div>
                    </div>
                    <div class="text-right">
                        <button class="button settings-button button-blue" type="submit">Update Password</button>
                    </div>
                </form>
            </div>
            <div class="container" id="billing" style="display:none;">
                <div class="settings-title">Billing</div>
                @if (auth()->user()->membership == 0)
                    <p>You have no active VIP subscription. <a href="#" target="_blank">Click here</a> to upgrade!</p>
                @else
                    <p>You currently have an active <span style="color:{{ auth()->user()->membershipColor() }};">{{ auth()->user()->membershipLevel() }}</span> subscription until {{ auth()->user()->membership_expires }}.</p>
                    <p><a href="https://billing.stripe.com/p/login/cN2g1f6hf2RDcZG6oo" target="_blank">Click here</a> to manage your subscription.</p>
                    <p>Please submit a ticket at <a href="https://discord.gg/nB4crCZmpn">https://discord.gg/nB4crCZmpn</a> for billing help.</p>
                @endif
            </div>
            <div class="container" id="connections" style="display:none;">
                <div class="settings-title">Connections</div>
                    @if(auth()->user()->hasLinkedDiscord())
                        <div style="display:inline-block;border: 0px solid #ccc;padding:5px;border-radius:10px;background-color:#5865F2;color:white;">
                            <i class="fa-brands fa-discord"></i> {{ auth()->user()->discord->username }}
                            <a href="#" style="color:red;font-weight:bold;font-size:13px;" onclick="event.preventDefault();document.getElementById('unlink-discord').submit();">Unlink</a>
                            <form method="POST" id="unlink-discord" action="{{ route('discord.unlink') }}" class="d-none">
                                @csrf
                            </form>
                        </div>
                    @else
                        <a href="{{ route('discord.connect') }}" class="button" style="background-color:#5865F2!important;"><i class="fa-brands fa-discord"></i> Discord</a>
                    @endif
            </div>
        @endif  
    </body>
</x-app-layout>