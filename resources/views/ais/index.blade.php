<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>
    <div class="card">
        <div class="card-header">Options</div>
        <div class="card-body">
            <div class="row">
                @forelse ($options as $option)
                    <div class="col-6 col-md-3 text-center">
                        <a href="{{ $option[0] }}" style="color:{{ $option[3] }};text-decoration:none;">
                            <div class="card" style="border:1px solid #ced4da;box-shadow:none;">
                                <div class="card-body">
                                    <i class="{{ $option[2] }} mb-2" style="font-size:60px;"></i>
                                    <div class="text-truncate" style="font-weight:600;">{{ $option[1] }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col">You do not have access to any admin panel features.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>