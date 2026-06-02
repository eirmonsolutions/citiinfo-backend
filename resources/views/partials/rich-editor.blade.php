@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ === 'undefined' || !$.fn.summernote) return;

        $('textarea.rich-editor').summernote({
            height: 380,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'table']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
@endpush
