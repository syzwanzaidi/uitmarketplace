/**
 * Cart selection: per-item and per-seller checkboxes, live total, and the
 * checkout button state.
 */
export function initCart() {
    const form = document.getElementById('cartForm');
    if (!form) return;

    const items = [...form.querySelectorAll('[data-cart-item]')];
    const sellers = [...form.querySelectorAll('[data-cart-seller]')];
    const all = form.querySelector('[data-cart-all]');
    const totalEl = document.querySelector('[data-cart-total]');
    const countEl = document.querySelector('[data-cart-count]');
    const submit = document.querySelector('[data-cart-submit]');
    const money = new Intl.NumberFormat('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    const refresh = () => {
        const selected = items.filter((i) => i.checked);
        const total = selected.reduce((sum, i) => sum + parseFloat(i.dataset.price), 0);

        totalEl.textContent = `RM ${money.format(total)}`;
        countEl.textContent = selected.length;
        submit.disabled = selected.length === 0;

        sellers.forEach((s) => {
            const group = items.filter((i) => i.dataset.seller === s.dataset.seller);
            s.checked = group.length > 0 && group.every((i) => i.checked);
            s.indeterminate = !s.checked && group.some((i) => i.checked);
        });

        if (all) {
            all.checked = items.length > 0 && selected.length === items.length;
            all.indeterminate = selected.length > 0 && !all.checked;
        }
    };

    items.forEach((i) => i.addEventListener('change', refresh));
    sellers.forEach((s) => s.addEventListener('change', () => {
        items.filter((i) => i.dataset.seller === s.dataset.seller).forEach((i) => { i.checked = s.checked; });
        refresh();
    }));
    all?.addEventListener('change', () => {
        items.forEach((i) => { i.checked = all.checked; });
        refresh();
    });

    refresh();
}
