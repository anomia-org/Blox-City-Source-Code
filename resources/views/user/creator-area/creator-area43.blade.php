<x-app-layout>
    <x-slot name="title">Creator Area</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="row">
        <div class="col-md-3">
            <h4>Creator Area</h4>
            <div class="card card-body">
                <div class="nav flex-column nav-pills mb-3 mb-md-0" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <div class="text-muted my-1 text-sm fw-bold text-center">
                        MANAGE
                    </div>
                    <button class="nav-link active" id="mange-shirt-tab" data-bs-toggle="pill" data-bs-target="#manageShirt" type="button" role="tab" aria-controls="ManageShirts" aria-selected="false">
                        Manage Shirts
                    </button>
                    <button class="nav-link" id="manage-pants-tab" data-bs-toggle="pill" data-bs-target="#managePants" type="button" role="tab" aria-controls="ManagePants" aria-selected="false">
                        Manage Pants
                    </button>
                    <button class="nav-link" id="manage-ads-tab" data-bs-toggle="pill" data-bs-target="#manageAds" type="button" role="tab" aria-controls="ManageAds" aria-selected="false">
                        Manage Ads
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane show active" id="manageShirt" role="tabpanel" aria-labelledby="manage-shirts-tab" tabindex="0">
                    <h4>Manage Shirts</h4>
                    <div class="card card-body">
                        @foreach($shirts as $shirt)
                        <div class="section">
                            <div class="row align-items-center">
                                <div class="col-4 col-md-2">
                                    <img src="{{ $shirt->get_render() }}" class="img-fluid" />
                                </div>
                                <div class="col-8 col-md-10">
                                    <div class="d-flex align-items-center">
                                        <a href="#" class="min-w-0 auto">
                                            <div class="text-xl fw-bold text-light">
                                                {{ $shirt->name }}
                                            </div>
                                            <div class="text-muted truncate fw-normal pb-1">
                                                {{ $shirt->desc }}
                                            </div>
                                            @if($shirt->cash > 0) <span class="d-block d-md-inline text-success fw-semibold me-2"> <i class="currency currency-cash currency-md currency-align text-md me-1"></i>{{ $shirt->cash }} </span> @endif
                                            @if($shirt->coins > 0)<span class="d-block d-md-inline text-warning fw-semibold me-2"> <i class="currency currency-coin currency-lg currency-align text-md me-1"></i>{{ $shirt->coins }} </span> @endif
                                        </a>
                                        <div class="dropdown ps-2">
                                            <button class="text-xl bg-transparent border-0 p-0 text-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical text-xl"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                                <li>
                                                    <span class="text-center dropdown-item-text notification-dropdown-title p-0">More</span>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider mb-1" />
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('ad.creator-area.advertise.view', $shirt) }}">Advertise</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('market.item.edit', $shirt->id) }}" class="dropdown-item">Modify</a>
                                                </li>
                                                {{-- <li>
                                                    <a class="dropdown-item text-danger" role="button" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</a>
                                                </li> --}}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane" id="managePants" role="tabpanel" aria-labelledby="manage-pants-tab" tabindex="0">
                    <h4>Manage Pants</h4>
                    <div class="card card-body">
                        @foreach($pants as $pant)
                        <div class="section">
                            <div class="row align-items-center">
                                <div class="col-4 col-md-2">
                                    <img src="{{ $pant->get_render() }}" class="img-fluid" />
                                </div>
                                <div class="col-8 col-md-10">
                                    <div class="d-flex align-items-center">
                                        <a href="#" class="min-w-0 auto">
                                            <div class="text-xl fw-bold text-light">
                                                {{ $pant->name }}
                                            </div>
                                            <div class="text-muted truncate fw-normal pb-1">
                                                {{ $pant->desc }}
                                            </div>
                                            @if($pant->cash > 0) <span class="d-block d-md-inline text-success fw-semibold me-2"> <i class="currency currency-cash currency-md currency-align text-md me-1"></i>{{ $pant->cash }} </span> @endif
                                            @if($pant->coins > 0)<span class="d-block d-md-inline text-warning fw-semibold me-2"> <i class="currency currency-coin currency-lg currency-align text-md me-1"></i>{{ $pant->coins }} </span> @endif
                                        </a>
                                        <div class="dropdown ps-2">
                                            <button class="text-xl bg-transparent border-0 p-0 text-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical text-xl"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                                <li>
                                                    <span class="text-center dropdown-item-text notification-dropdown-title p-0">More</span>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider mb-1" />
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('ad.creator-area.advertise.view', $pant) }}">Advertise</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('market.item.edit', $pant->id) }}" class="dropdown-item">Modify</a>
                                                </li>
                                                {{-- <li>
                                                    <a class="dropdown-item text-danger" role="button" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</a>
                                                </li> --}}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane" id="manageAds" role="tabpanel" aria-labelledby="ad-tab" tabindex="0">
                    <h4>Manage Ads</h4>
                    <div class="card card-body">
                        @foreach($ads as $ad)
                        <div class="section">
                            <div class="row align-items-center">
                                <div class="col-12 text-center">
                                    <img src="{{ $ad->image_path }}" class="img-fluid mb-3 rounded mx-auto" />
                                </div>
                                <div class="col-4 col-md-2">
                                    <img src="{{ $ad->item->get_render() }}" class="img-fluid" />
                                </div>
                                <div class="col-8 col-md-10">
                                    <div class="d-flex align-items-center">
                                        <a href="#" class="min-w-0 auto">
                                            <div class="text-xs fw-bold text-muted mb-1">
                                                ADVERTISMENT FOR
                                            </div>
                                            <div class="text-xl fw-bold text-light">
                                                {{ $ad->item->name }}
                                            </div>
                                            <div class="text-muted truncate fw-normal pb-1">
                                                {{ $ad->item->desc }}
                                            </div>
                                        </a>
                                        <div class="dropdown ps-2">
                                            <button class="text-xl bg-transparent border-0 p-0 text-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical text-xl"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                                <li>
                                                    <span class="text-center dropdown-item-text notification-dropdown-title p-0">More</span>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider mb-1" />
                                                </li>
                                                <li>
                                                    <a href="{{ route('ad.manage', $ad) }}" class="dropdown-item">View More</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" onclick="selectedAd={{ $ad->id }}" role="button" data-bs-toggle="modal" data-bs-target="#deleteAdModal">Take Down</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- Take Down Ad Modal --->
                                        <div class="modal fade" id="deleteAdModal" tabindex="-1" aria-labelledby="deleteAdModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header border-bottom-0 pb-0">
                                                        <h4 class="modal-title" id="deleteAdModalLabel">Delete</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to take down this advertisement?
                                                        <span class="text-danger fw-semibold">This action cannot be undone.</span>
                                                    </div>
                                                    <div class="modal-footer border-top-0 pt-0">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            Cancel
                                                        </button>
                                                        <button type="button" id="deletead-button" class="btn btn-danger">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="script">
        <script>
            $("#deletead-button").click(() => {
                $.get("/ads/take-down/" + selectedAd, function(data, status){
                    if(data.success){
                        $("#deleteAdModal").modal("hide")
                        window.location.href = '/creator-area'
                    }
                })
            })
        </script>
    </x-slot>
</x-app-layout>