@props(['paginator', 'ariaLabel' => 'Pagination'])

@if($paginator->hasPages())
<div class="pagination-wrap">
    <nav aria-label="{{ $ariaLabel }}">
        <ul class="pagination mb-0">
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->previousPageUrl() ?? '#' }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            <li class="page-item {{ $paginator->currentPage() == $page ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
            </li>
            @endforeach

            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $paginator->nextPageUrl() ?? '#' }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
@endif
