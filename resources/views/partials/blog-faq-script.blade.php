<script>
document.addEventListener('DOMContentLoaded', () => {
    const repeater = document.getElementById('blogFaqRepeater');
    const addBtn = document.getElementById('addBlogFaqBtn');
    if (!repeater || !addBtn) return;

    const reindex = () => {
        repeater.querySelectorAll('.blog-faq-row').forEach((row, i) => {
            row.querySelectorAll('[name]').forEach((el) => {
                if (el.name.includes('[question]')) el.name = `faq_items[${i}][question]`;
                if (el.name.includes('[answer]')) el.name = `faq_items[${i}][answer]`;
            });
        });
        updateRemoveButtons();
    };

    const updateRemoveButtons = () => {
        const rows = repeater.querySelectorAll('.blog-faq-row');
        rows.forEach((row) => {
            const btn = row.querySelector('.btn-remove-blog-faq');
            if (btn) btn.style.display = rows.length > 1 ? '' : 'none';
        });
    };

    addBtn.addEventListener('click', () => {
        const i = repeater.querySelectorAll('.blog-faq-row').length;
        const tpl = document.createElement('div');
        tpl.className = 'blog-faq-row border rounded p-3 mb-3';
        tpl.innerHTML = `
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Question</label>
                    <input type="text" name="faq_items[${i}][question]" class="form-control" placeholder="e.g. What does NAP stand for in SEO?">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Answer</label>
                    <textarea name="faq_items[${i}][answer]" class="form-control" rows="3" placeholder="Write the answer..."></textarea>
                </div>
                <div class="col-md-12 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-blog-faq">Remove</button>
                </div>
            </div>
        `;
        repeater.appendChild(tpl);
        reindex();
    });

    repeater.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-remove-blog-faq');
        if (!btn) return;
        btn.closest('.blog-faq-row')?.remove();
        if (!repeater.querySelectorAll('.blog-faq-row').length) {
            addBtn.click();
        }
        reindex();
    });

    reindex();
});
</script>
