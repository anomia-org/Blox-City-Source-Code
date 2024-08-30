<x-admin-layout>
    <x-slot name="title">Reports</x-slot>
    <div class="row">
        <div class="col-12">
            <h3>Pending Reports</h3>
            <div class="card overflow-hidden mb-4 px-0">
                <hr class="d-block d-md-none mt-0 mb-2 mx-3">
                <div class="bg-primary-800 border-top border-bottom border-primary text-primary-200 py-2 px-3 d-none d-md-block">
                    <div class="row opacity-75 fw-semibold text-sm text-uppercase">
                        <div class="col-md-5">Reported by</div>
                        <div class="col-md-4">Content Type</div>
                        <div class="col-md-3">Action</div>
                    </div>
                </div>
                <div class="py-2 px-3 mb-2">

                    @foreach($reports as $report)
                    <div class="section">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <div class="d-block d-md-none text-xs fw-bold text-muted text-uppercase mb-2">
                                    Reported by:
                                </div>
                                <div class="d-flex gap-2 align-items-center">
                                    <img src="{{ $report->owner->get_headshot() }}" class="img-fluid rounded-circle bg-gray-500 mr-2" width="41">
                                    <a href="{{ route('user.profile', $report->owner->id) }}" class="text-decoration-none">
                                        <div class="fw-semibold">{{ $report->owner->username }}</div>
                                        <div class="d-block truncate fw-normal text-muted text-sm">
                                            <span class="text-success">{{ $report->created_at->diffForHumans() }}</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="d-block d-md-none text-xs fw-bold text-muted text-uppercase mt-3">
                                    Content Type:
                                </div>
                                <span class="d-block truncate mt-1 text-sm">{{ $report->type() }}</span>
                            </div>
                            <div class="col-md-3">
                                <div class="d-block d-md-none text-xs fw-bold text-muted text-uppercase mt-3 mb-2">
                                    Action:
                                </div>
                                <a type="button" href="{{ route('ais.report', $report->id) }}" class="btn btn-primary btn-sm">View</a>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-center">{{ $reports->links('vendor.pagination.default') }}</div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>