@extends('layouts.app')

@section('title', 'Business Categories – Australia Local Business Directory | Citiinfo')

@section('meta_description', 'Explore business categories on Citiinfo Australia. Find restaurants, salons, plumbers, hospitals, skip bin hire, towing services, real estate agents and more local businesses across Australia.')

@section('meta_keywords', '')

@section('content')

<section class="banner-area">
    <div class="container">
        <div class="banner-text">
            <h1>Explore Business Categories in Australia</h1>
        </div>
    </div>
</section>

<section class="filter-section">
    <div class="container">
        <form method="GET" action="{{ url()->current() }}" id="filterForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="filter-left">

                        {{-- SEARCH --}}
                        <div class="filter-field">
                            <span>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                    <path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2" />
                                </svg>
                            </span>

                            <input
                                type="text"
                                name="q"
                                class="filter-input"
                                placeholder="Search..."
                                value="{{ request('q') }}"
                                id="searchInput"
                                autocomplete="off" />
                        </div>

                        <div class="filter-right">
                            {{-- SORT --}}
                            <div class="sort-wrap filter-select" data-type="sort" id="sortSelect">
                                <span class="sort-label">Sort by:</span>

                                <input type="hidden" name="sort" id="sortInput" value="{{ request('sort', 'name_asc') }}">

                                <button type="button" class="sort-btn" id="sortBtn">
                                    <span id="sortValue">
                                        @php
                                        $sortLabels = [
                                        'name_asc' => 'Name (A-Z)',
                                        'name_desc' => 'Name (Z-A)',
                                        'date_asc' => 'Date (Oldest)',
                                        'date_desc' => 'Date (Newest)',
                                        ];
                                        @endphp
                                        {{ $sortLabels[request('sort', 'name_asc')] ?? 'Name (A-Z)' }}
                                    </span>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                        <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                    </svg>
                                </button>

                                <div class="dropdown" id="sortDropdown" style="display:none;">
                                    <button type="button" class="dd-item" data-value="name_asc">Name (A-Z)</button>
                                    <button type="button" class="dd-item" data-value="name_desc">Name (Z-A)</button>
                                    <button type="button" class="dd-item" data-value="date_asc">Date (Oldest)</button>
                                    <button type="button" class="dd-item" data-value="date_desc">Date (Newest)</button>
                                </div>
                            </div>
                        </div>
                        <div class="filter-bar">
                            <div class="filter-right">
                                <div class="results">
                                    Showing
                                    <span id="resultsCount">{{ $categories->count() }}</span>
                                    of
                                    <span id="resultsTotal">{{ $categories->total() }}</span>
                                    results
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </form>
    </div>
</section>

<section class="category-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-title">
                    <h2>Browse Business Categories Across Australia</h2>
                </div>
            </div>
        </div>

        {{-- ✅ SAME PAGE RESULT WRAPPER --}}
        <div id="categoryResults">
            <div class="row">
                <div class="category-item-grid">
                    @forelse($categories as $category)
                    <div class="category-item category-item-two">
                        <!-- <div class="category-img">
                            <img
                                src="{{ $category->image ? asset('storage/' . $category->image) : asset('assets/images/saloon.jpg') }}"
                                alt="{{ $category->name }}"
                                
                                decoding="async">

                            <div class="category-overlay">
                                <div class="category-content">
                                    <a href="{{ route('list.category', ['category' => \Illuminate\Support\Str::slug($category->name)]) }}">
                                        <i class="ti-link"></i>
                                    </a>
                                </div>
                            </div>
                        </div> -->

                        <div class="info">
                            <div class="icon">
                                @if(!empty($category->categoryimage))
                                <img
                                    src="{{ asset('storage/' . $category->categoryimage) }}"
                                    alt="{{ $category->name }}"
                                    decoding="async"
                                    style="width:40px;height:40px;object-fit:contain;filter: brightness(0);">
                                @else
                                <span>-</span>
                                @endif
                            </div>

                            <h3 class="title">
                                <a href="{{ route('list.category', ['category' => \Illuminate\Support\Str::slug($category->name)]) }}">
                                    {{ $category->name }}
                                </a>
                            </h3>

                            <span class="listing">
                                {{ $category->listings_count }} Listing{{ $category->listings_count != 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center">
                        <p>No categories found.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ✅ PAGINATION --}}
        <div id="paginationWrapper">
            @if($categories->hasPages())
            <div class="pagination-wrap">
                <nav aria-label="Category Pagination">
                    <ul class="pagination">

                        <li class="page-item {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ $categories->previousPageUrl() ?? '#' }}"
                                aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                        <li class="page-item {{ $categories->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                        @endforeach

                        <li class="page-item {{ $categories->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link"
                                href="{{ $categories->nextPageUrl() ?? '#' }}"
                                aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
            @endif
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortBtn = document.getElementById('sortBtn');
        const sortDropdown = document.getElementById('sortDropdown');
        const sortInput = document.getElementById('sortInput');
        const sortValue = document.getElementById('sortValue');
        const ddItems = document.querySelectorAll('.dd-item');
        const searchInput = document.getElementById('searchInput');

        let debounceTimer;

        function slugify(text) {
            return text.toString().toLowerCase().trim()
                .replace(/&/g, 'and')
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }

        function buildCategoryHtml(items) {
            if (!items.length) {
                return `
                <div class="row">
                    <div class="col-12 text-center">
                        <p>No categories found.</p>
                    </div>
                </div>
            `;
            }

            let html = `<div class="row"><div class="category-item-grid">`;

            items.forEach(category => {
                html += `
                <div class="category-item category-item-two">
                    <div class="category-img">
                        <img
                            src="${category.image_url}"
                            alt="${category.name}"
                            loading="lazy"
                            decoding="async">

                        <div class="category-overlay">
                            <div class="category-content">
                                <a href="/category/${category.slug}">
                                    <i class="ti-link"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="info">
                        <div class="icon">
                            ${category.categoryimage_url
                                ? `<img src="${category.categoryimage_url}" alt="${category.name}" loading="lazy" decoding="async" style="width:40px;height:40px;object-fit:contain;filter: brightness(0);">`
                                : `<span>-</span>`
                            }
                        </div>

                        <h3 class="title">
                            <a href="/category/${category.slug}">${category.name}</a>
                        </h3>

                        <span class="listing">
                            ${category.listings_count} Listing${category.listings_count != 1 ? 's' : ''}
                        </span>
                    </div>
                </div>
            `;
            });

            html += `</div></div>`;
            return html;
        }

        function buildPagination(currentPage, lastPage, q, sort) {
            if (lastPage <= 1) {
                return '';
            }

            let html = `
            <div class="pagination-wrap">
                <nav aria-label="Category Pagination">
                    <ul class="pagination">
        `;

            const prevPage = currentPage - 1;
            const nextPage = currentPage + 1;

            html += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link ajax-page-link" href="#" data-page="${prevPage}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        `;

            for (let page = 1; page <= lastPage; page++) {
                html += `
                <li class="page-item ${currentPage === page ? 'active' : ''}">
                    <a class="page-link ajax-page-link" href="#" data-page="${page}">${page}</a>
                </li>
            `;
            }

            html += `
            <li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
                <a class="page-link ajax-page-link" href="#" data-page="${nextPage}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        `;

            html += `</ul></nav></div>`;
            return html;
        }

        function fetchCategories(page = 1) {
            const q = searchInput.value;
            const sort = sortInput.value;

            fetch(`{{ url()->current() }}?q=${encodeURIComponent(q)}&sort=${encodeURIComponent(sort)}&page=${page}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(response => {
                    document.getElementById('categoryResults').innerHTML = buildCategoryHtml(response.data);
                    document.getElementById('paginationWrapper').innerHTML = buildPagination(response.current_page, response.last_page, q, sort);
                    document.getElementById('resultsCount').textContent = response.data.length;
                    document.getElementById('resultsTotal').textContent = response.total;
                })
                .catch(error => {
                    console.error('Search error:', error);
                });
        }

        if (sortBtn) {
            sortBtn.addEventListener('click', function() {
                sortDropdown.style.display = sortDropdown.style.display === 'block' ? 'none' : 'block';
            });
        }

        ddItems.forEach(item => {
            item.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                const text = this.textContent.trim();

                sortInput.value = value;
                sortValue.textContent = text;
                sortDropdown.style.display = 'none';

                fetchCategories(1);
            });
        });

        document.addEventListener('click', function(e) {
            const sortSelect = document.getElementById('sortSelect');
            if (sortSelect && !sortSelect.contains(e.target)) {
                sortDropdown.style.display = 'none';
            }
        });

        searchInput.addEventListener('keyup', function() {
            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(() => {
                fetchCategories(1);
            }, 400);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('ajax-page-link')) {
                e.preventDefault();

                const parentLi = e.target.closest('.page-item');
                if (parentLi.classList.contains('disabled') || parentLi.classList.contains('active')) {
                    return;
                }

                const page = e.target.getAttribute('data-page');
                fetchCategories(page);
            }
        });
    });
</script>
@endpush