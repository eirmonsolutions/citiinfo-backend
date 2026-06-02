@extends('layouts.superadmin')

@section('title', 'Dashboard')

@section('content')

<main class="main-dashboard">

    <div class="top-heading">
        <h1>Feature List</h1>

        {{-- FIX: target should be #featureModal --}}
        <button type="button" class="theme-btn" data-bs-toggle="modal" data-bs-target="#featureModal">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Feature
        </button>
    </div>

    <section class="table-section table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Feature Name</th>
                    <th>Icon</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="featureTableBody">
                @forelse($features as $feature)
                <tr>
                    <td>{{ $features->firstItem() + $loop->index }}</td>

                    <td>{{ $feature->name }}</td>

                    <td>
                        <div class="category-icon">
                            @if(!empty($feature->icon_image))
                            <img
                                src="{{ asset('storage/'.$feature->icon_image) }}"
                                alt="{{ $feature->name }}"
                                style="height:34px;width:34px;object-fit:contain;">
                            @else
                            -
                            @endif
                        </div>
                    </td>

                    <td>
                        <form method="POST" action="{{ route('superadmin.feature.toggle-status', $feature) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="badge {{ $feature->is_active ? 'bg-label-success' : 'bg-label-danger' }}"
                                style="border:0;">
                                {{ $feature->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>

                    <td>
                        <div class="action-buttons">
                            <button
                                type="button"
                                class="btn-icon btn-edit"
                                data-bs-toggle="modal"
                                data-bs-target="#editfeatureModal"
                                data-id="{{ $feature->id }}"
                                data-name="{{ $feature->name }}"
                                data-icon-image="{{ $feature->icon_image }}"
                                data-active="{{ $feature->is_active ? 1 : 0 }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-pencil">
                                    <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                    <path d="m15 5 4 4" />
                                </svg>
                            </button>

                            <form method="POST"
                                action="{{ route('superadmin.feature.destroy', $feature) }}"
                                class="deletefeatureForm d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-trash-2">
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
                @empty
                <tr>
                    <td colspan="5" class="text-center">No features found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($features->hasPages())
        <div class="pagination-wrap">
            <nav aria-label="Feature Pagination">
                <ul class="pagination">

                    <li class="page-item {{ $features->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ $features->previousPageUrl() ?? '#' }}"
                            aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    @foreach ($features->getUrlRange(1, $features->lastPage()) as $page => $url)
                    <li class="page-item {{ $features->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @endforeach

                    <li class="page-item {{ $features->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link"
                            href="{{ $features->nextPageUrl() ?? '#' }}"
                            aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
        @endif
    </section>


</main>

<!-- Add Feature Modal -->
<!-- Add Feature Modal -->
<div class="modal fade CategoryModal" id="featureModal" tabindex="-1" aria-labelledby="featureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-5" id="featureModalLabel">Add New Feature</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="featureForm" method="POST" action="{{ route('superadmin.feature.store') }}" class="modal-body" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="featureName" class="form-label">Feature Name</label>
                    <input type="text" id="featureName" name="name" class="form-input" placeholder="Enter feature name" required>
                </div>

                {{-- ✅ Feature Icon Image Upload (same design as your example) --}}
                <div class="form-group">
                    <label class="form-label">Feature Icon Image</label>

                    <div class="category-img-upload" id="featureIconUpload" style="cursor:pointer;">
                        <div class="upload-area">
                            <img id="addFeatureIconPreview" src="" style="display:none;max-height:60px;margin-bottom:8px;">

                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-cloud-upload-icon lucide-cloud-upload">
                                <path d="M12 13v8" />
                                <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
                                <path d="m8 17 4-4 4 4" />
                            </svg>

                            <p class="upload-text">Drop icon image here or click</p>
                        </div>

                        <input type="file" id="featureIconFile" name="icon_image" accept="image/*" hidden>
                    </div>
                </div>

                <div class="form-group-inline">
                    <div>
                        <label class="form-label">Active Status</label>
                        <p class="form-help text-muted">Set the feature as active or inactive</p>
                    </div>

                    <label class="switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="featureStatus" name="is_active" value="1" checked>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Feature</button>
                </div>
            </form>

        </div>
    </div>
</div>





<!-- Edit Feature Modal -->
<!-- Edit Feature Modal -->
<div class="modal fade CategoryModal" id="editfeatureModal" tabindex="-1" aria-labelledby="editfeatureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editfeatureModalLabel">Edit Feature</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editfeatureForm" method="POST" class="modal-body" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Feature Name</label>
                    <input type="text" name="name" id="editName" class="form-input" placeholder="Enter feature name" required>
                </div>

                {{-- ✅ Feature Icon Image Upload --}}
                <div class="form-group">
                    <label class="form-label">Feature Icon Image</label>

                    <div class="category-img-upload" id="editFeatureIconUpload" style="cursor:pointer;">
                        <div class="upload-area">
                            <img id="editFeatureIconPreview" src="" style="display:none;max-height:60px;margin-bottom:8px;">

                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-cloud-upload-icon lucide-cloud-upload">
                                <path d="M12 13v8" />
                                <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
                                <path d="m8 17 4-4 4 4" />
                            </svg>

                            <p class="upload-text">Drop icon image here or click</p>
                        </div>

                        <input type="file" id="editFeatureIconFile" name="icon_image" accept="image/*" hidden>
                    </div>

                    {{-- hidden old image path (optional for JS use only) --}}
                    <input type="hidden" id="editOldIconImage" value="">
                </div>

                <div class="form-group-inline">
                    <div>
                        <label class="form-label">Active Status</label>
                        <p class="form-help text-muted">Set the feature as active or inactive</p>
                    </div>

                    <label class="switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="editActive" name="is_active" value="1">
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Feature</button>
                </div>

            </form>

        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ---------- ADD MODAL: click upload box -> open file picker ----------
        const featureIconUpload = document.getElementById('featureIconUpload');
        const featureIconFile = document.getElementById('featureIconFile');
        const addPreview = document.getElementById('addFeatureIconPreview');

        if (featureIconUpload && featureIconFile) {
            featureIconUpload.addEventListener('click', () => featureIconFile.click());

            featureIconFile.addEventListener('change', function() {
                const file = this.files && this.files[0];
                if (!file) return;

                const url = URL.createObjectURL(file);
                addPreview.src = url;
                addPreview.style.display = 'block';
            });
        }

        // ---------- EDIT MODAL: click upload box -> open file picker ----------
        const editFeatureIconUpload = document.getElementById('editFeatureIconUpload');
        const editFeatureIconFile = document.getElementById('editFeatureIconFile');
        const editPreview = document.getElementById('editFeatureIconPreview');

        if (editFeatureIconUpload && editFeatureIconFile) {
            editFeatureIconUpload.addEventListener('click', () => editFeatureIconFile.click());

            editFeatureIconFile.addEventListener('change', function() {
                const file = this.files && this.files[0];
                if (!file) return;

                const url = URL.createObjectURL(file);
                editPreview.src = url;
                editPreview.style.display = 'block';
            });
        }

        // ---------- EDIT BUTTON: open modal fill data + set form action ----------
        document.querySelectorAll('.btn-edit').forEach(function(btn) {
            btn.addEventListener('click', function() {

                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name') || '';
                const active = this.getAttribute('data-active') === '1';

                // IMPORTANT: yaha image path bhejo: data-icon-image="features/xxxx.png"
                const iconImage = this.getAttribute('data-icon-image') || '';

                const editName = document.getElementById('editName');
                const editActive = document.getElementById('editActive');

                if (editName) editName.value = name;
                if (editActive) editActive.checked = active;

                // show existing image preview in edit
                if (editPreview) {
                    if (iconImage) {
                        editPreview.src = `{{ asset('storage') }}/${iconImage}`;
                        editPreview.style.display = 'block';
                    } else {
                        editPreview.src = '';
                        editPreview.style.display = 'none';
                    }
                }

                // set edit form action
                let url = `{{ route('superadmin.feature.update', ':id') }}`;
                url = url.replace(':id', id);

                const editForm = document.getElementById('editfeatureForm');
                if (editForm) editForm.action = url;

                // clear previous selected file
                if (editFeatureIconFile) editFeatureIconFile.value = '';
            });
        });

    });
</script>



@endsection