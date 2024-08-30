<x-app-layout>
    <meta name="community-info" data-id="{{ $guild->id }}">
    <x-slot name="title">Manage Community</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="card p-3 px-4 mb-3 text-center text-md-start">
        <div class="d-md-flex justify-content-between align-items-center">
            <div class="mb-3 mb-md-0">
                <div class="text-3xl fw-semibold">Manage "{{ $guild->name }}"</div>
                <div class="text-muted">
                    Creator: <a href="{{ route('user.profile', $guild->owner->id) }}" class="fw-semibold">{{ $guild->owner->username }}</a>
                </div>
            </div>
            <div class="text-center">
                <div class="text-bold">
                    <span class="text-success text-xl me-2">
                        <i class="currency currency-cash currency-md currency-align text-2xl me-1"></i> {{ $guild->get_short_num($guild->cash) }}
                    </span>
                    <span class="text-warning text-xl">
                        <i class="currency currency-coin currency-lg currency-align text-2xl me-1"></i> {{ $guild->get_short_num($guild->coins) }}
                    </span>
                </div>
                <div class="text-muted">VAULT</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="nav flex-column nav-pills mb-3 mb-md-0" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="info-tab" data-bs-toggle="pill" data-bs-target="#information" type="button" role="tab" aria-controls="info" aria-selected="true">
                    Information
                </button>
                <button class="nav-link" id="memebers-tab" data-bs-toggle="pill" data-bs-target="#members" type="button" role="tab" aria-controls="members" aria-selected="true">
                    Members
                </button>
                <button class="nav-link" id="roles-tab" data-bs-toggle="pill" data-bs-target="#roles" type="button" role="tab" aria-controls="role" aria-selected="true">
                    Roles
                </button>
            </div>
        </div>
        <div class="col-md-8">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane show active" id="information" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
                    <h4>Community Information</h4>
                    <div class="card card-body mb-3">
                        <form method="POST" action="{{ route('groups.edit.general.post', $guild->id) }}" enctype="multipart/form-data">
                        @csrf
                            <div class="row gy-4">
                                <div class="col-lg-5">
                                    <div class="text-xs text-center mb-2 fw-bold text-muted mb-1">
                                        LOGO
                                    </div>
                                    <div class="community-logo-manage bg-secondary p-3 mx-auto">
                                        <img src="{{ $guild->thumbnail() }}" class="img-fluid" />
                                    </div>
                                    @if($guild->owner->id == auth()->user()->id)
                                        <div class="text-xs mb-1 mt-4 fw-bold text-muted mb-1">
                                            UPDATE LOGO
                                        </div>
                                        <input class="form-control" type="file" name="image" id="formFile" />
                                    @endif
                                </div>
                                <div class="col-lg-7">
                                    <div class="text-xs fw-bold text-muted mb-1">
                                        NAME
                                    </div>
                                    <div class="text-3xl fw-semibold mb-3 truncate">{{ $guild->name }}</div>
                                    <div class="text-xs fw-bold text-muted mb-1">
                                        DESCRIPTION
                                    </div>
                                    <textarea rows="8" class="form-control" id="desc" name="desc" placeholder="(optional)" style="white-space: pre-line" @if($guild->owner->id != auth()->user()->id) disabled @endif>{{ $guild->desc }}</textarea>
                                </div>
                                @if($guild->owner->id == auth()->user()->id)
                                    <div class="text-end">
                                        <button class="btn btn-success">Save</button>
                                    </div>
                                @endif
                                
                            </div>
                        </form>
                    </div>
                    <h4>Ownership</h4>
                    <div class="card card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="min-w-0">
                                <div class="text-xs fw-bold text-muted mb-1">
                                    CURRENT OWNER
                                </div>
                                <div class="text-3xl fw-semibold">{{ $guild->owner->username }}</div>
                            </div>
                            @if($guild->owner->id == 3)
                                <button class="px-3 btn btn-danger truncate" data-bs-toggle="modal" data-bs-target="#ownershipModal">
                                    Change Ownership
                                </button>
                                <div class="modal fade" id="ownershipModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header border-bottom-0 pb-0">
                                                <h4 class="modal-title" id="exampleModalLabel">
                                                    Change Ownership
                                                </h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-xs fw-bold text-muted mb-1">
                                                    NEW OWNER'S USERNAME
                                                </div>
                                                <input type="text" class="form-control" placeholder="Username (Must be in community)" />
                                                <div class="text-xs fw-bold text-danger mt-1">
                                                    * This action cannot be undone
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0 pt-0">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <button type="button" class="btn btn-danger">
                                                    Change Ownership
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="settings" role="tabpanel" aria-labelledby="settings-tab" tabindex="0">
                    <h4>Settings</h4>
                    <div class="card card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="text-xl fw-semibold">
                                Private
                                <span class="text-muted text-xs">(Users must be accepted manually into community)</span>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="privateSwitch" @if($guild->is_private) checked @endif />
                            </div>
                        </div>
                        <hr />
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="text-xl fw-semibold">
                                Allow affiliate requests
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="affiliateSwitch" @if($guild->is_accepting_affiliates) checked @endif />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="members" role="tabpanel" aria-labelledby="members-tab" tabindex="0">
                    <h4>Members</h4>
                    <div class="card p-3">

                        <div class="row">
                            <div class="col-md-12">
                                <select class="form-control" id="rankSelect">
                                    @foreach ($guild->ranks() as $rank)
                                        <option value="{{ $rank->rank }}">{{ $rank->name }} ({{ $rank->memberCount() }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row" id="members-data">
                        </div>

                        <div class="mt-2 text-end">
                            <button class="btn btn-success">Save</button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="vault" role="tabpanel" aria-labelledby="vault-tab" tabindex="0">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <h4 class="p-0 m-0">Vault</h4>
                        <button class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#vaultModal">
                            Payout Currency
                        </button>
                        <div class="modal fade" id="vaultModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h4 class="modal-title" id="exampleModalLabel">
                                            Payout Currency
                                        </h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-muted text-xs fw-bold mb-1">
                                            RECIPIENT'S USERNAME
                                        </div>
                                        <input type="text" class="form-control" placeholder="Username (Must be in community)" />
                                        <div class="text-muted text-xs fw-bold mt-2">
                                            CURRENCY
                                        </div>
                                        <div class="d-flex gap-2 py-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payInCash" id="payInCash" checked />
                                                <label class="form-check-label" for="payInCash">
                                                    Cash
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payInCoins" id="payInCoins" />
                                                <label class="form-check-label" for="payInCoins">
                                                    Coins
                                                </label>
                                            </div>
                                        </div>
                                        <div class="input-parent has-icon">
                                            <i class="text-success bi bi-cash-stack"></i>
                                            <input type="text" class="form-control" placeholder="Cash" />
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pt-0">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Cancel
                                        </button>
                                        <button type="button" class="btn btn-success">
                                            Payout
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card p-3 px-4 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fw-semibold">Total Revenue</div>
                            <div>
                                <span class="text-success me-2">
                                    <i class="bi bi-cash-stack text-lg align-middle"></i>
                                    10,000
                                </span>
                                <span class="text-warning">
                                    <i class="bi bi-coin text-lg align-middle"></i> 110,210
                                </span>
                            </div>
                        </div>
                    </div>
                    <h4>Transactions</h4>
                    <div class="card card-body mb-3">
                        <div class="section">
                            <div class="row gy-3">
                                <div class="col-md-3">
                                    <div class="text-xs fw-bold text-muted mb-1">DONOR</div>
                                    <a href="#" class="text-xl fw-semibold text-light d-block truncate">SidStan123</a>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-xs fw-bold text-muted mb-1">
                                        RECIPIENT
                                    </div>
                                    <a href="#" class="text-xl fw-semibold text-light d-block truncate">Sid</a>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-xs fw-bold text-muted mb-1">AMOUNT</div>
                                    <div class="text-xl fw-semibold">
                                        <span class="text-success me-2">
                                            <i class="bi bi-cash-stack text-lg align-middle"></i>
                                            10,000
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-xs fw-bold text-muted mb-1">DATE</div>
                                    <div class="text-xl fw-semibold text-light">8h ago</div>
                                </div>
                            </div>
                        </div>
                        <div class="section">
                            <div class="row gy-3">
                                <div class="col-md-3">
                                    <div class="text-xs fw-bold text-muted mb-1">DONOR</div>
                                    <a href="#" class="text-xl fw-semibold text-light d-block truncate">SidStan123</a>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-xs fw-bold text-muted mb-1">
                                        RECIPIENT
                                    </div>
                                    <a href="#" class="text-xl fw-semibold text-light d-block truncate">Sid</a>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-xs fw-bold text-muted mb-1">AMOUNT</div>
                                    <div class="text-xl fw-semibold">
                                        <span class="text-warning me-2">
                                            <i class="bi bi-coin text-lg align-middle"></i>
                                            10,000
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-xs fw-bold text-muted mb-1">DATE</div>
                                    <div class="text-xl fw-semibold text-light">8h ago</div>
                                </div>
                            </div>
                        </div>
                        <div class="section">
                            <div class="row gy-3">
                                <div class="col-md-3">
                                    <div class="text-xs fw-bold text-muted mb-1">DONOR</div>
                                    <a href="#" class="text-xl fw-semibold text-light d-block truncate">SidStan123</a>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-xs fw-bold text-muted mb-1">
                                        RECIPIENT
                                    </div>
                                    <a href="#" class="text-xl fw-semibold text-light d-block truncate">Sid</a>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-xs fw-bold text-muted mb-1">AMOUNT</div>
                                    <div class="text-xl fw-semibold">
                                        <span class="text-success me-2">
                                            <i class="bi bi-cash-stack text-lg align-middle"></i>
                                            10,000
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-xs fw-bold text-muted mb-1">DATE</div>
                                    <div class="text-xl fw-semibold text-light">8h ago</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>Sales</h4>
                    <div class="card card-body">
                        <div class="section">
                            <div class="row align-items-center">
                                <div class="col-4 col-md-2">
                                    <img src="img/avatar/blocky.png" class="img-fluid" />
                                </div>
                                <div class="col-8 col-md-10">
                                    <a href="#" class="min-w-0">
                                        <div class="text-xl fw-bold text-light">
                                            Kyle Fan Shirt
                                        </div>
                                        <div class="text-muted truncate fw-normal pb-1">
                                            wear this shirt if u truly love kyle irl and ingame
                                            and wish to have millions of children with him owo
                                        </div>
                                        <span class="d-block d-md-inline text-success fw-semibold me-2">
                                            <i class="bi bi-cash-stack text-md me-1 align-middle"></i>10,000
                                        </span>
                                        <span class="d-block d-md-inline text-warning fw-semibold me-2">
                                            <i class="bi bi-coin text-md me-1 align-middle"></i>10,000
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="section">
                            <div class="row align-items-center">
                                <div class="col-4 col-md-2">
                                    <img src="img/avatar/blocky.png" class="img-fluid" />
                                </div>
                                <div class="col-8 col-md-10">
                                    <a href="#" class="min-w-0">
                                        <div class="text-xl fw-bold text-light">
                                            Kyle Fan Shirt
                                        </div>
                                        <div class="text-muted truncate fw-normal pb-1">
                                            wear this shirt if u truly love kyle irl and ingame
                                            and wish to have millions of children with him owo
                                        </div>
                                        <span class="d-block d-md-inline text-success fw-semibold me-2">
                                            <i class="bi bi-cash-stack text-md me-1 align-middle"></i>10,000
                                        </span>
                                        <span class="d-block d-md-inline text-warning fw-semibold me-2">
                                            <i class="bi bi-coin text-md me-1 align-middle"></i>10,000
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="section">
                            <div class="row align-items-center">
                                <div class="col-4 col-md-2">
                                    <img src="img/avatar/blocky.png" class="img-fluid" />
                                </div>
                                <div class="col-8 col-md-10">
                                    <a href="#" class="min-w-0">
                                        <div class="text-xl fw-bold text-light">
                                            Kyle Fan Shirt
                                        </div>
                                        <div class="text-muted truncate fw-normal pb-1">
                                            wear this shirt if u truly love kyle irl and ingame
                                            and wish to have millions of children with him owo
                                        </div>
                                        <span class="d-block d-md-inline text-success fw-semibold me-2">
                                            <i class="bi bi-cash-stack text-md me-1 align-middle"></i>10,000
                                        </span>
                                        <span class="d-block d-md-inline text-warning fw-semibold me-2">
                                            <i class="bi bi-coin text-md me-1 align-middle"></i>10,000
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="roles" role="tabpanel" aria-labelledby="vault-tab" tabindex="0">
                    <div class="d-flex align-items-end justify-content-between mb-2">
                        <h4 class="p-0 m-0">Roles</h4>
                        <button class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#roleModal">
                            Create Role
                        </button>
                    </div>
                    <div class="card card-body">
                        Coming soon
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            var id = parseInt($('meta[name="community-info"]').attr('data-id'));

            $(() => {
                getMembers(1, 1);

                $('#rankSelect').change(function() {
                    getMembers($(this).val(), 1);
                });
            });

            function getMembers(rank, page) {
                $.get('members', { id, rank, page }).done((data) => {
                    $('#members-data').html('');

                    if (typeof data.error !== 'undefined')
                        return $('#members-data').html(`<div class="col">${data.error}</div>`);

                    $.each(data.members, function() {
                        $('#members-data').append(`
                        <div class="col-3 text-center mb-3">
                            <div class="dropdown float-right">
                                <button class="text-xl bg-transparent border-0 p-0 text-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical text-xl"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                    <li>
                                        <span class="text-center dropdown-item-text notification-dropdown-title p-0">Actions</span>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider mb-1">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" role="button" data-bs-toggle="modal" data-bs-target="#modifyModal">Modify</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#">Kick</a>
                                    </li>
                                </ul>
                            </div>
                            <a href="${this.url}">
                                <img src="${this.thumbnail}" class="img-fluid mb-1" />
                            </a>
                            
                            <a href="${this.url}" class="d-block truncate fw-semibold">${this.username}</a>
                        </div>`);
                    });

                    if (data.total_pages > 1) {
                        const previousDisabled = (data.current_page == 1) ? 'disabled' : '';
                        const nextDisabled = (data.current_page == data.total_pages) ? 'disabled' : '';
                        const previousPage = data.current_page - 1;
                        const nextPage = data.current_page + 1;

                        $('#members-data').append(`
                            <ul class="pagination justify-content-center">
                                <li class="page-item">
                                    <button class="page-link" onclick="getMembers(${rank}, ${previousPage})" ${previousDisabled} aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </button>
                                </li>
                                <span class="text-muted mx-2 mt-1">${data.current_page} of ${data.total_pages}</span>
                                <li class="page-item">
                                    <button class="page-link" onclick="getMembers(${rank}, ${nextPage})" ${nextDisabled} aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </button>
                                </li>
                            </ul>`);
                    }
                }).fail(() => $('#members-data').html('<div class="col">Unable to get members.</div>'));
            }
        </script>
    </x-slot>

</x-app-layout>