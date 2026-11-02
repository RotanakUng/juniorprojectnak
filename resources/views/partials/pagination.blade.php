@if ($paginator->hasPages())
    <nav class="tp-pagination" role="navigation" aria-label="Pagination">
        <div class="tp-pagination-info">
            Showing <span class="tp-pagination-bold">{{ $paginator->firstItem() }}</span> to <span class="tp-pagination-bold">{{ $paginator->lastItem() }}</span> of <span class="tp-pagination-bold">{{ $paginator->total() }}</span> orders
        </div>

        <div class="tp-pagination-controls">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="tp-page-btn tp-page-nav is-disabled" aria-disabled="true" aria-label="Previous page">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    <span>Prev</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="tp-page-btn tp-page-nav" rel="prev" aria-label="Previous page">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    <span>Prev</span>
                </a>
            @endif

            {{-- Page Numbers --}}
            <div class="tp-page-numbers">
                @foreach ($elements as $element)
                    {{-- Three Dots Separator --}}
                    @if (is_string($element))
                        <span class="tp-page-dots" aria-disabled="true">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="tp-page-btn tp-page-num is-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="tp-page-btn tp-page-num">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="tp-page-btn tp-page-nav" rel="next" aria-label="Next page">
                    <span>Next</span>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            @else
                <span class="tp-page-btn tp-page-nav is-disabled" aria-disabled="true" aria-label="Next page">
                    <span>Next</span>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
