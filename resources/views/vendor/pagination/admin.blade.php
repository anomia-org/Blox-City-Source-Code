@if ($paginator->hasPages() || $paginator->total() > 0)
    @foreach ($elements as $element)
        @if (is_array($element))
            @foreach ($element as $page => $url)
                <a href="{{ $url }}" class="page {{ ($page == $paginator->currentPage()) ? 'active' : '' }}">{{ number_format($page) }}</a>
            @endforeach
        @endif
    @endforeach
@endif