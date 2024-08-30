<x-admin-layout>
    <x-slot name="title">Asset Approval</x-slot>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills nav-justified" role="tablist">
                <li class="nav-item">
                    <a href="{{ route('ais.assets.index', 'items') }}" class="nav-link @if ($category == 'items') active @endif">Items ({{ number_format($totalItems) }})</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ais.assets.index', 'logos') }}" class="nav-link @if ($category == 'logos') active @endif">Logos ({{ number_format($totalLogos) }})</a>
                </li><!-- 
                <li class="nav-item">
                    <a href=" route('ais.assets.index', 'thumbnails') " class="nav-link if ($category == 'thumbnails') active endif">Thumbnails ( number_format($totalThumbnails) )</a>
                </li> -->
            </ul>
        </div>
    </div>
    <div class="row mb-2">
        @forelse ($assets as $asset)
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ $asset->source }}" class="mb-2" target="_blank">
                            <img src="{{ $asset->image }}">
                        </a>
                        <div class="text-truncate">
                            <a href="{{ $asset->url }}" style="font-weight:600;" target="_blank">{{ $asset->name }}</a>
                            <div style="margin-top:-5px;">
                                <strong>{{ ($category == 'items') ? 'Creator' : 'Owner' }}:</strong>
                                <a href="{{ $asset->creator_url }}" target="_blank">{{ $asset->creator_name }}</a>
                            </div>
                        </div>
                        @if ($category == 'items')
                            <div style="margin-top:-5px;">
                                <strong>Type:</strong>
                                <span>{{ $asset->get_type() }}</span>
                            </div>
                        @endif
                        <hr>
                        <form action="{{ route('ais.assets.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $asset->id }}">
                            <input type="hidden" name="type" value="{{ $type }}">
                            <div class="row">
                                <div class="col">
                                    <button class="green w-100" name="action" value="approve"><i class="fas fa-check"></i></button>
                                </div>
                                <div class="col">
                                    <button class="red w-100" name="action" value="deny"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col">There are currently no pending {{ $category }}.</div>
        @endforelse
    </div>
    <div class="pages">{{ $assets->onEachSide(1) }}</div>
</x-admin-layout>