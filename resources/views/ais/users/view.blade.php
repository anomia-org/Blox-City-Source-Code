<x-admin-layout>
    <x-slot name="title">{{ $user->username }}</x-slot>
    <h3>{{ $user->username }}</h3>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Thumbnail</div>
                <div class="text-center card-body">
                    <img src="{{ $user->get_avatar() }}">
                    <a href="{{ route('user.profile', $user->id) }}" class="mt-3 button blue small w-100" target="_blank"><i class="fas fa-link"></i> View Profile</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Linked Accounts</div>
                <div class="card-body" style="max-height:250px;overflow-y:auto;">
                    @forelse ($user->accountsLinkedByIP() as $account)
                        <div class="row">
                            <div class="col-9 col-md-8 text-truncate"><a href="{{ route('ais.users.view', $account->id) }}">{{ $account->username }}</a></div>
                            <div class="text-right col-3 col-md-4">{{ number_format($account->times_linked) }}x</div>
                        </div>
                    @empty
                        <p>None found.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Information</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4"><strong>Username</strong></div>
                        <div class="text-right col-8">{{ $user->username }}</div>
                        @if (auth()->user()->power >= 2)
                            <div class="col-4"><strong>Email</strong></div>
                            <div class="text-right col-8">{{ $user->email }}</div>
                        @endif
                        <div class="col-4"><strong>Verified Email</strong></div>
                        <div class="text-right col-8">{{ ($user->email_verified_at != null) ? 'Yes' : 'No' }}</div>
                        <div class="col-4"><strong> Has Discord?</strong></div>
                        <div class="text-right col-8">{{ ($user->discord) ? 'Yes' : 'No' }}</div>
                        <div class="col-4"><strong>Discord ID</strong></div>
                        <div class="text-right col-8">{{ $user->discord->id ?? 'N/A' }}</div>
                        <div class="col-4"><strong>Discord User</strong></div>
                        <div class="text-right col-8">{{ $user->discord->username ?? 'N/A' }}</div>
                        <div class="col-3"><strong>Last IP</strong></div>
                        <div class="text-right col-9">###.###.###.###</div>
                        <div class="col-4"><strong>Join Date</strong></div>
                        <div class="text-right col-8">{{ $user->created_at->format('M d, Y') }}</div>
                        <div class="col-4"><strong>Last Seen</strong></div>
                        <div class="text-right col-8">{{ $user->last_online->format('M d, Y') }}</div>
                        <div class="col-6"><strong>Forum Posts</strong></div>
                        <div class="text-right col-6">{{ number_format($user->posts()) }}</div>
                        <div class="col-4"><strong>Coins</strong></div>
                        <div class="text-right col-8 bits-text">
                            <span class="bits-icon"></span>
                            <span>{{ number_format($user->coins) }}</span>
                        </div>
                        <div class="col-4"><strong>Cash</strong></div>
                        <div class="text-right col-8 bucks-text">
                            <span class="bucks-icon"></span>
                            <span>{{ number_format($user->cash) }}</span>
                        </div>
                        <!-- if (config('event.enabled'))
                            <div class="col-4"><strong> config('event.currency_name')</strong></div>
                            <div class="text-right col-8"><i class="config('event.currency_class')"></i> number_format($user->event_currency)</div>
                        endif -->
                        <!-- <div class="col-6"><strong>Money Spent</strong></div>
                        <div class="text-right col-6">$ number_format($user->moneySpent()) </div> -->
                        @if ($user->membership > 0)
                            <div class="col-6"><strong>Membership</strong></div>
                            <div class="text-right col-6"><span class="text-white badge" style="background-color:{{ $user->membershipColor() }}!important;">{{ $user->get_membership() }}</span></div>
                            <div class="col-6"><strong>Membership Until</strong></div>
                            <div class="text-right col-6">{{ $user->membership_expires }}</div>
                        @endif
                        <div class="col-4"><strong>Is Online</strong></div>
                        <div class="text-right col-8">{{ ($user->isOnline()) ? 'Yes' : 'No' }}</div>
                        <div class="col-4"><strong>Is Staff</strong></div>
                        <div class="text-right col-8">{{ ($user->power > 0) ? 'Yes' : 'No' }}</div>
                        <div class="col-4"><strong>Status</strong></div>
                        <div class="text-right col-8">
                            @if ($user->deleted > 0)
                                <span class="text-white badge bg-danger">BANNED</span>
                            @elseif ($user->email_verified_at == null)
                                <span class="badge bg-warning">EMAIL NOT VERIFIED</span>
                            @else
                                <span class="text-white badge bg-success">OK</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Settings</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6"><strong>Theme</strong></div>
                        <div class="text-right col-6">{{ ucwords(str_replace('_', ' ', $user->theme)) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <form action="{{ route('ais.users.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">

                @if (auth()->user()->power > 0)
                    <div class="card">
                        <div class="card-header">Account Actions</div>
                        <div class="card-body">
                            @if (auth()->user()->power > 0 && !$user->deleted > 0)
                                <a href="{{ route('ais.users.ban.index', $user->id) }}" class="mb-2 button red w-100">
                                    <i class="mr-1 fas fa-ban"></i>
                                    <span>Ban</span>
                                </a>
                            @endif

                            @if (auth()->user()->power > 0 && $user->deleted > 0)
                                <button class="mb-2 green w-100" name="action" value="unban">
                                    <i class="mr-1 fa fa-ban"></i>
                                    <span>Unban</span>
                                </button>
                            @endif

                            @if (auth()->user()->power > 2 && !$ipBanned)
                                <button class="mb-2 red w-100" name="action" value="ip_ban">
                                    <i class="mr-1 fa fa-key"></i>
                                    <span>Ban IP</span>
                                </button>
                            @elseif (auth()->user()->power > 2 && $ipBanned)
                                <button class="mb-2 green w-100" name="action" value="ip_ban">
                                    <i class="mr-1 fa fa-key"></i>
                                    <span>Unban IP</span>
                                </button>
                            @endif

                            @if(auth()->user()->power > 2 && $user->beta > 0)
                                <button class="mb-2 red w-100" name="action" value="beta">
                                    <i class="mr-1 fa fa-trash"></i>
                                    <span>Remove Beta</span>
                                </button>
                            @elseif(auth()->user()->power > 2 && $user->beta == 0)
                                <button class="mb-2 green w-100" name="action" value="beta">
                                    <i class="mr-1 fa fa-key"></i>
                                    <span>Grant Beta</span>
                                </button>
                            @endif

                            @if (auth()->user()->power > 3)
                                <button class="mb-2 red w-100" name="action" value="password">
                                    <i class="mr-1 fa fa-key"></i>
                                    <span>Reset Password</span>
                                </button>
                            @endif

                            @if(auth()->user()->power > 2)
                                <button class="mb-2 red w-100" name="action" value="scrub_username">
                                    <i class="mr-1 fa fa-trash"></i>
                                    <span>Scrub Username</span>
                                </button>
                                <button class="mb-2 red w-100" name="action" value="scrub_all_posts">
                                    <i class="mr-1 fa fa-trash"></i>
                                    <span>Scrub All Posts</span>
                                    <span>(CANNOT UNDO)</span>
                                </button>
                            @endif

                            @if (auth()->user()->power > 0)
                                <button class="mb-2 red w-100" name="action" value="scrub_description">
                                    <i class="mr-1 fa fa-trash"></i>
                                    <span>Scrub Description/Blurb</span>
                                </button>
                                <button class="mb-2 red w-100" name="action" value="scrub_forum_signature">
                                    <i class="mr-1 fa fa-trash"></i>
                                    <span>Scrub Forum Signature</span>
                                </button>
                            @endif
                            @if (auth()->user()->power > 2)
                                @if ($user->membership > 0)
                                    <button class="mb-2 red w-100" name="action" value="remove_membership">
                                        <i class="mr-1 fa fa-trash"></i>
                                        <span>Remove Membership</span>
                                    </button>
                                @endif
                                <div class="mb-1"></div>
                                <label for="length">Membership</label>
                                <div class="input-group">
                                    <select class="form-control" name="membership_type">
                                        <option value="bronze" selected>Bronze</option>
                                        <option value="silver">Silver</option>
                                        <option value="gold">Gold</option>
                                    </select>
                                    <select class="form-control" name="membership_length">
                                        <option value="1_month" selected>1 Month</option>
                                        <option value="3_months">3 Months</option>
                                        <option value="6_months">6 Months</option>
                                        <option value="1_year">1 Year</option>
                                        <option value="forever">Forever</option>
                                    </select>
                                    <div class="input-group-append">
                                        <button class="green small" style="border-radius:0 5px 5px 0;" name="action" value="grant_membership">Grant</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if (auth()->user()->power > 2)
                        <div class="card">
                            <div class="card-header">Economy Actions</div>
                            <div class="card-body">
                                <a href="{{ route('ais.users.manage.index', ['currency', $user->id]) }}" class="mb-2 button blue w-100">
                                    <i class="mr-1 fas fa-money-bill-alt"></i>
                                    <span>Manage Currency</span>
                                </a>
                                <a href="{{ route('ais.users.manage.index', ['inventory', $user->id]) }}" class="mb-2 button blue w-100">
                                    <i class="mr-1 fas fa-box"></i>
                                    <span>Manage Inventory</span>
                                </a>
                            </div>
                        </div>
                    @endif
                @endif

            </form>
        </div>
    </div>
</x-admin-layout>