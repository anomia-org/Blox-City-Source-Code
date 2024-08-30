<x-admin-layout> 
    <x-slot name="title">{{ $title }}</x-slot>
    @if ($type == 'currency')
        <div class="row">
            @if (auth()->user()->power > 2)
                <div class="col-md">
                    <div class="card">
                        <div class="card-header">Give Cash</div>
                        <div class="card-body">
                            <form action="{{ route('ais.users.manage.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <input type="hidden" name="action" value="give_currency">
                                <input type="hidden" name="currency" value="cash">
                                <input class="form-control mb-3" name="amount" type="number" min="1" placeholder="Amount" required>
                                <button class="bucks" type="submit">Give</button>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Give Coins</div>
                        <div class="card-body">
                            <form action="{{ route('ais.users.manage.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <input type="hidden" name="action" value="give_currency">
                                <input type="hidden" name="currency" value="coins">
                                <input class="form-control mb-3" name="amount" type="number" min="1" placeholder="Amount" required>
                                <button class="bits" type="submit">Give</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (auth()->user()->power > 2)
                <div class="col-md">
                    <div class="card">
                        <div class="card-header">Take Cash</div>
                        <div class="card-body">
                            <form action="{{ route('ais.users.manage.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <input type="hidden" name="action" value="take_currency">
                                <input type="hidden" name="currency" value="cash">
                                <input class="form-control mb-3" name="amount" type="number" min="1" placeholder="Amount" required>
                                <button class="red" type="submit">Take</button>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Take Coins</div>
                        <div class="card-body">
                            <form action="{{ route('ais.users.manage.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <input type="hidden" name="action" value="take_currency">
                                <input type="hidden" name="currency" value="coins">
                                <input class="form-control mb-3" name="amount" type="number" min="1" placeholder="Amount" required>
                                <button class="red" type="submit">Take</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @elseif ($type == 'inventory')
        <div class="row">
            @if (auth()->user()->power > 2)
                <div class="col-md">
                    <div class="card">
                        <div class="card-header">Give Item</div>
                        <div class="card-body">
                            <form action="{{ route('ais.users.manage.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <input type="hidden" name="action" value="give_items">
                                <input class="form-control mb-3" name="item_id" type="number" min="1" placeholder="Item ID" required>
                                <button class="green" type="submit">Give</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (auth()->user()->power > 2)
                <div class="col-md">
                    <div class="card">
                        <div class="card-header">Take Item</div>
                        <div class="card-body">
                            <form action="{{ route('ais.users.manage.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <input type="hidden" name="action" value="take_items">
                                <input class="form-control mb-3" name="item_id" type="number" min="1" placeholder="Item ID" required>
                                <button class="red" type="submit">Take</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
</x-admin-layout>