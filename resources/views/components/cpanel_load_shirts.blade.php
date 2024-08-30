@foreach($items as $item)
    <div class="cell small-6 medium-2 market-item-cell">
        <a href="{{ route('market.item', $item->id) }}" title="{{ $item->name }}">
            <img class="market-item-thumbnail" src="{{ $item->get_render() }}">
        </a>
        <a href="{{ route('market.item', $item->id) }}" class="market-item-name" title="{{ $item->name }}">{{ $item->name }}</a>
		@if($item->creator_type == 1)
            <div class="market-item-creator">Creator: <a href="{{ route('user.profile', $item->creator_id) }}">{{ $item->owner->username }}</a></div>
        @elseif($item->creator_type == 2)
            <div class="market-item-creator">Creator: <a href="{{ route('groups.view', $item->creator_id) }}">{{ $item->owner->name }}</a></div>
        @endif
    </div>
@endforeach