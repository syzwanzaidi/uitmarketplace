/**
 * Small, page-agnostic behaviours driven by data attributes.
 */
export function initUi() {
    // Flash messages rendered by the server.
    document.querySelectorAll('.toast').forEach((el) => {
        window.bootstrap?.Toast.getOrCreateInstance(el, { delay: 5000 }).show();
    });

    // <form data-confirm="Are you sure?">
    document.addEventListener('submit', (event) => {
        const message = event.target.dataset?.confirm;
        if (message && !window.confirm(message)) {
            event.preventDefault();
            event.stopImmediatePropagation();
        }
    }, true);

    // Prevent double submits on slow connections.
    document.addEventListener('submit', (event) => {
        if (event.defaultPrevented || event.target.method?.toLowerCase() === 'get') return;
        event.target.querySelectorAll('button[type="submit"], button:not([type])').forEach((btn) => {
            btn.disabled = true;
            if (btn.dataset.loading) btn.textContent = btn.dataset.loading;
        });
    });

    // <input type="file" data-preview="#imgId">
    document.querySelectorAll('input[type="file"][data-preview]').forEach((input) => {
        input.addEventListener('change', () => {
            const target = document.querySelector(input.dataset.preview);
            const file = input.files?.[0];
            if (!target || !file) return;
            target.src = URL.createObjectURL(file);
            target.classList.remove('d-none');
            target.nextElementSibling?.classList.contains('ph') && target.nextElementSibling.classList.add('d-none');
        });
    });

    // Auto-submit selects: <select data-autosubmit>
    document.querySelectorAll('[data-autosubmit]').forEach((el) => {
        el.addEventListener('change', () => el.form?.requestSubmit());
    });
}

/** Escape text for safe insertion into HTML. */
export function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text ?? '';
    return div.innerHTML;
}

export function showToast(message, type = 'success') {
    let stack = document.querySelector('.toast-stack');
    if (!stack) {
        stack = document.createElement('div');
        stack.className = 'toast-stack';
        document.body.appendChild(stack);
    }

    const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill';
    const el = document.createElement('div');
    el.className = `toast is-${type}`;
    el.setAttribute('role', 'status');
    el.innerHTML = `<div class="toast-body"><i class="bi ${icon}"></i><div class="flex-grow-1">${escapeHtml(message)}</div>
        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="toast" aria-label="Close"></button></div>`;
    stack.appendChild(el);
    window.bootstrap?.Toast.getOrCreateInstance(el, { delay: 5000 }).show();
}
