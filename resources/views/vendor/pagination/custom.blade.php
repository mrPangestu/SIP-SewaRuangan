{{-- resources/views/vendor/pagination/custom.blade.php --}}
@if ($paginator->hasPages())
    <ul class="pagination justify-content-center">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link rounded-circle mx-1">
                    <i class="fas fa-chevron-left"></i>
                </span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link rounded-circle mx-1" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link rounded-circle mx-1">{{ $element }}</span>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page">
                            <span class="page-link rounded-circle mx-1">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link rounded-circle mx-1" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link rounded-circle mx-1" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link rounded-circle mx-1">
                    <i class="fas fa-chevron-right"></i>
                </span>
            </li>
        @endif
    </ul>
@endif
@push('style')
    <style>
        /* Add this to your existing CSS */
.pagination {
    display: flex;
    list-style: none;
    padding: 0;
}

.page-item .page-link {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: transparent;
    color: #6c757d;
    font-weight: 600;
    transition: all 0.3s;
}

.page-item .page-link:hover {
    background-color: rgba(67, 97, 238, 0.1);
    color: var(--primary-color);
}

.page-item.active .page-link {
    background-color: var(--primary-color);
    color: white;
}

.page-item.disabled .page-link {
    color: #dee2e6;
    pointer-events: none;
}
    </style>
@endpush