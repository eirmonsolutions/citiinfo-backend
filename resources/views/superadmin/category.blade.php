@extends('layouts.superadmin')

@section('title', 'Dashboard')

@section('content')



<main class="main-dashboard">

    <div class="top-heading">
        <h1>Category List</h1>
        <button type="button" class="theme-btn" data-bs-toggle="modal" data-bs-target="#CategoryModal">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Category
        </button>
    </div>


    <section class="table-section table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category Image</th>
                    <th>Category Name</th>
                    <th>Category Image Icon</th>

                    {{-- ✅ NEW: Homepage show checkbox --}}
                    <th>Homepage (Max 6)</th>

                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="categoryTableBody">
                @foreach($categories as $index => $cat)
                <tr>
                    <td>{{ $categories->firstItem() + $index }}</td>

                    <td>
                        <div class="category-img">
                            @if($cat->image)
                            <img src="{{ asset('storage/'.$cat->image) }}" alt="">
                            @endif
                        </div>
                    </td>

                    <td>{{ $cat->name }}</td>

                    <td>
                        <div class="category-icon">
                            @if($cat->categoryimage)
                            <img
                                src="{{ asset('storage/'.$cat->categoryimage) }}"
                                alt="{{ $cat->name }}"
                                style="width:32px;height:32px;object-fit:contain;filter: brightness(0);">
                            @else
                            <span>-</span>
                            @endif
                        </div>
                    </td>

                    {{-- ✅ NEW: is_home toggle --}}
                    <td>
                        <form method="POST" action="{{ route('superadmin.category.toggle-home', $cat->id) }}" class="homeToggleForm">
                            @csrf
                            @method('PATCH')

                            <label class="switch" style="margin:0;">
                                <input type="checkbox"
                                    name="is_home"
                                    value="1"
                                    {{ $cat->is_home ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </form>
                    </td>

                    <td>
                        <form method="POST" action="{{ route('superadmin.category.toggle-status', $cat->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="badge {{ $cat->is_active ? 'bg-label-success' : 'bg-label-danger' }}" style="border:0;">
                                {{ $cat->is_active ? 'Active' : 'InActive' }}
                            </button>
                        </form>
                    </td>

                    <td>
                        <div class="action-buttons">
                            <button
                                class="btn-icon btn-edit"
                                data-bs-toggle="modal"
                                data-bs-target="#editCategoryModal"
                                data-id="{{ $cat->id }}"
                                data-title="{{ $cat->title }}"
                                data-description="{{ $cat->description }}"
                                data-keyword="{{ $cat->keyword }}"
                                data-name="{{ $cat->name }}"
                                data-active="{{ $cat->is_active ? 1 : 0 }}"
                                data-image="{{ $cat->image ? asset('storage/'.$cat->image) : '' }}"
                                data-categoryimage="{{ $cat->categoryimage ? asset('storage/'.$cat->categoryimage) : '' }}">

                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-pencil">
                                    <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                    <path d="m15 5 4 4" />
                                </svg>
                            </button>

                            <form method="POST" action="{{ route('superadmin.category.destroy', $cat) }}" class="deleteCategoryForm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                        <path d="M3 6h18" />
                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-wrap">
            <nav aria-label="Category Pagination">
                <ul class="pagination">

                    {{-- ✅ Previous --}}
                    <li class="page-item {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ $categories->previousPageUrl() ?? '#' }}"
                            aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    {{-- ✅ Page Numbers --}}
                    @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                    <li class="page-item {{ $categories->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @endforeach

                    {{-- ✅ Next --}}
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

    </section>




</main>





<!-- Modal -->
<div class="modal fade CategoryModal" id="CategoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="categoryModalLabel">Add New Category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="categoryForm" method="POST" action="{{ route('superadmin.category.store') }}"
                enctype="multipart/form-data" class="modal-body">
                @csrf

                <div class="form-group">
                    <label for="categoryName" class="form-label">Category Name</label>
                    <input type="text" id="categoryName" name="name" class="form-input"
                        placeholder="Enter category name" required>
                </div>

                {{-- ✅ Meta Title --}}
                <div class="form-group">
                    <label for="categoryTitle" class="form-label">Meta Title</label>
                    <input type="text" id="categoryTitle" name="title" class="form-input"
                        placeholder="Enter meta title">
                </div>

                {{-- ✅ Meta Description --}}
                <div class="form-group">
                    <label for="categoryDescription" class="form-label">Meta Description</label>
                    <textarea id="categoryDescription" name="description" class="form-input"
                        rows="4" placeholder="Enter meta description"></textarea>
                </div>

                {{-- ✅ Meta Keyword --}}
                <div class="form-group">
                    <label for="categoryKeyword" class="form-label">Meta Keyword</label>
                    <textarea id="categoryKeyword" name="keyword" class="form-input"
                        rows="3" placeholder="Enter meta keywords comma separated"></textarea>
                </div>

                {{-- ✅ Category Icon Image --}}
                <div class="form-group">
                    <label class="form-label">Category Icon Image</label>
                    <div class="category-img-upload" id="categoryIconUpload" style="cursor:pointer;">
                        <div class="upload-area">
                            <img id="addIconPreview" src="" style="display:none;max-height:60px;margin-bottom:8px;filter: brightness(0);">

                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-cloud-upload-icon lucide-cloud-upload">
                                <path d="M12 13v8" />
                                <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
                                <path d="m8 17 4-4 4 4" />
                            </svg>
                            <p class="upload-text">Drop icon image here or click</p>
                        </div>

                        <input type="file" id="categoryIconFile" name="categoryimage" accept="image/*" hidden>
                    </div>
                </div>

                {{-- ✅ Category Image --}}
                <div class="form-group">
                    <label class="form-label">Category Image</label>
                    <div class="category-img-upload" id="categoryImg" style="cursor:pointer;">
                        <div class="upload-area">
                            <img id="addImagePreview" src="" style="display:none;max-height:60px;margin-bottom:8px;">

                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-cloud-upload-icon lucide-cloud-upload">
                                <path d="M12 13v8" />
                                <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
                                <path d="m8 17 4-4 4 4" />
                            </svg>
                            <p class="upload-text">Drop image here or click</p>
                        </div>

                        <input type="file" id="categoryImageFile" name="image" accept="image/*" hidden>
                    </div>
                </div>

                <div class="form-group-inline">
                    <div>
                        <label class="form-label">Active Status</label>
                        <p class="form-help text-muted">Set the category as active or inactive</p>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="categoryStatus" name="is_active" value="1" checked>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal for editing categories -->

<div class="modal fade CategoryModal" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Edit Category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editCategoryForm" method="POST" enctype="multipart/form-data" class="modal-body">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="name" id="editName" class="form-input"
                        placeholder="Enter category name" required>
                </div>

                {{-- ✅ Edit Meta Title --}}
                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="title" id="editTitle" class="form-input"
                        placeholder="Enter meta title">
                </div>

                {{-- ✅ Edit Meta Description --}}
                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="description" id="editDescription" class="form-input"
                        rows="4" placeholder="Enter meta description"></textarea>
                </div>

                {{-- ✅ Edit Meta Keyword --}}
                <div class="form-group">
                    <label class="form-label">Meta Keyword</label>
                    <textarea name="keyword" id="editKeyword" class="form-input"
                        rows="3" placeholder="Enter meta keywords comma separated"></textarea>
                </div>

                {{-- ✅ Edit Category Icon Image --}}
                <div class="form-group">
                    <label class="form-label">Category Icon Image</label>
                    <div class="category-img-upload" id="editIconUploadBox" style="cursor:pointer;">
                        <div class="upload-area">
                            <img id="editIconPreview" src="" style="display:none;max-height:60px;margin-bottom:8px;filter: brightness(0);">

                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-cloud-upload-icon lucide-cloud-upload">
                                <path d="M12 13v8" />
                                <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
                                <path d="m8 17 4-4 4 4" />
                            </svg>
                            <p class="upload-text">Drop icon image here or click</p>
                        </div>

                        <input type="file" id="editCategoryIconFile" name="categoryimage" accept="image/*" hidden>
                    </div>
                </div>

                {{-- ✅ Edit Category Image --}}
                <div class="form-group">
                    <label class="form-label">Category Image</label>
                    <div class="category-img-upload" id="editUploadBox" style="cursor:pointer;">
                        <div class="upload-area">
                            <img id="editPreview" src="" style="display:none;height: 160px;margin-bottom:8px;">

                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-cloud-upload-icon lucide-cloud-upload">
                                <path d="M12 13v8" />
                                <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
                                <path d="m8 17 4-4 4 4" />
                            </svg>
                            <p class="upload-text">Drop image here or click</p>
                        </div>

                        <input type="file" id="editImage" name="image" accept="image/*" hidden>
                    </div>
                </div>

                <div class="form-group-inline">
                    <div>
                        <label class="form-label">Active Status</label>
                        <p class="form-help text-muted">Set the category as active or inactive</p>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="editActive" name="is_active" value="1">
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    document.querySelectorAll('.homeToggleForm input[type="checkbox"]').forEach(chk => {
        chk.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
</script>


<script>
    // =========================
    // ✅ ADD MODAL: open file dialogs
    // =========================
    document.getElementById('categoryIconUpload')?.addEventListener('click', () => {
        document.getElementById('categoryIconFile').click();
    });

    document.getElementById('categoryImg')?.addEventListener('click', () => {
        document.getElementById('categoryImageFile').click();
    });

    // ✅ ADD MODAL: icon preview
    document.getElementById('categoryIconFile')?.addEventListener('change', function() {
        const file = this.files?.[0];
        if (!file) return;
        const preview = document.getElementById('addIconPreview');
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'inline-block';
    });

    // ✅ ADD MODAL: image preview
    document.getElementById('categoryImageFile')?.addEventListener('change', function() {
        const file = this.files?.[0];
        if (!file) return;
        const preview = document.getElementById('addImagePreview');
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'inline-block';
    });

    // ✅ Reset add modal when closed
    document.getElementById('CategoryModal')?.addEventListener('hidden.bs.modal', function() {
        const iconInput = document.getElementById('categoryIconFile');
        const imgInput = document.getElementById('categoryImageFile');

        const iconPreview = document.getElementById('addIconPreview');
        const imgPreview = document.getElementById('addImagePreview');

        if (iconInput) iconInput.value = '';
        if (imgInput) imgInput.value = '';

        if (iconPreview) {
            iconPreview.src = '';
            iconPreview.style.display = 'none';
        }
        if (imgPreview) {
            imgPreview.src = '';
            imgPreview.style.display = 'none';
        }
    });

    // =========================
    // ✅ EDIT MODAL: fill modal + set action + previews
    // =========================
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name || '';
            const title = this.dataset.title || '';
            const description = this.dataset.description || '';
            const keyword = this.dataset.keyword || '';
            const active = this.dataset.active === '1';

            const image = this.dataset.image || '';
            const categoryimage = this.dataset.categoryimage || '';

            // ✅ fill text fields
            document.getElementById('editName').value = name;
            document.getElementById('editTitle').value = title;
            document.getElementById('editDescription').value = description;
            document.getElementById('editKeyword').value = keyword;
            document.getElementById('editActive').checked = active;

            // ✅ category image preview (existing)
            const imgPreview = document.getElementById('editPreview');
            if (image) {
                imgPreview.src = image;
                imgPreview.style.display = 'inline-block';
            } else {
                imgPreview.src = '';
                imgPreview.style.display = 'none';
            }

            // ✅ icon image preview (existing)
            const iconPreview = document.getElementById('editIconPreview');
            if (categoryimage) {
                iconPreview.src = categoryimage;
                iconPreview.style.display = 'inline-block';
            } else {
                iconPreview.src = '';
                iconPreview.style.display = 'none';
            }

            // ✅ set form action
            let url = `{{ route('superadmin.category.update', ':id') }}`;
            url = url.replace(':id', id);
            document.getElementById('editCategoryForm').action = url;

            // ✅ reset file inputs
            document.getElementById('editImage').value = '';
            document.getElementById('editCategoryIconFile').value = '';
        });
    });

    // ✅ EDIT: open uploads
    document.getElementById('editIconUploadBox')?.addEventListener('click', () => {
        document.getElementById('editCategoryIconFile').click();
    });

    document.getElementById('editUploadBox')?.addEventListener('click', () => {
        document.getElementById('editImage').click();
    });

    // ✅ EDIT: icon preview on change
    document.getElementById('editCategoryIconFile')?.addEventListener('change', function() {
        const file = this.files?.[0];
        if (!file) return;
        const preview = document.getElementById('editIconPreview');
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'inline-block';
    });

    // ✅ EDIT: category image preview on change
    document.getElementById('editImage')?.addEventListener('change', function() {
        const file = this.files?.[0];
        if (!file) return;
        const preview = document.getElementById('editPreview');
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'inline-block';
    });

    // ✅ Reset edit modal when closed
    document.getElementById('editCategoryModal')?.addEventListener('hidden.bs.modal', function() {
        document.getElementById('editCategoryForm').reset();

        const imgPreview = document.getElementById('editPreview');
        const iconPreview = document.getElementById('editIconPreview');

        if (imgPreview) {
            imgPreview.src = '';
            imgPreview.style.display = 'none';
        }

        if (iconPreview) {
            iconPreview.src = '';
            iconPreview.style.display = 'none';
        }

        document.getElementById('editImage').value = '';
        document.getElementById('editCategoryIconFile').value = '';
    });
</script>



@endsection