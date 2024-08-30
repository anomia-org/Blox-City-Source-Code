<x-admin-layout>
    <x-slot name="title">Users</x-slot>
    <form action="{{ route('ais.users.index') }}" method="GET">
        <input class="mb-3 form-control" type="text" name="search" placeholder="Search..." value="{{ request()->search }}">
    </form>
    @if ($users->count() == 0)
        <p>No users were found.</p>
    @else
        <div class="card" style="border:none;">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Created</th>
                        <th>Last Seen</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td><a href="{{ route('ais.users.view', $user->id) }}">{{ $user->id }}</a></td>
                            <td><a href="{{ route('ais.users.view', $user->id) }}">{{ $user->username }}</a></td>
                            <td>{{ $user->created_at }}</td>
                            <td>{{ $user->updated_at }}</td>
                            <td>
                                @if ($user->deleted > 0)
                                    <span class="text-white badge bg-danger">BANNED</span>
                                @elseif ($user->email_verified_at == null)
                                    <span class="badge bg-warning">EMAIL NOT VERIFIED</span>
                                @else
                                    <span class="text-white badge bg-success">OK</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pages">{{ $users->links('vendor.pagination.admin') }}</div>
    @endif
</x-admin-layout>