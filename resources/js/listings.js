/**
 * One modal serves both "new listing" and "edit listing". Edit buttons carry
 * the listing as JSON in data-listing.
 */
export function initListingForm() {
    const modal = document.getElementById('listingModal');
    if (!modal) return;

    const form = modal.querySelector('form');
    const field = (name) => form.elements.namedItem(name);
    const title = modal.querySelector('.modal-title');
    const preview = form.querySelector('#listingPreview');
    const placeholder = preview.nextElementSibling;
    const submit = form.querySelector('button[type="submit"]');
    const createAction = form.dataset.createAction || form.getAttribute('action');

    modal.addEventListener('show.bs.modal', (event) => {
        const trigger = event.relatedTarget;

        // Reopened automatically after a failed submit: keep old input and errors.
        if (!trigger && form.dataset.keep === '1') {
            form.dataset.keep = '0';
            return;
        }

        const listing = trigger?.dataset.listing ? JSON.parse(trigger.dataset.listing) : null;

        form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback, .text-danger.small').forEach((el) => el.remove());

        form.reset();
        form.setAttribute('action', listing ? listing.update_url : createAction);
        field('_method').disabled = !listing;
        field('image').required = !listing;
        field('listing_id').value = listing?.id ?? '';
        title.textContent = listing ? 'Edit listing' : 'New listing';
        submit.textContent = listing ? 'Save changes' : 'Publish listing';

        ['name', 'category_id', 'description', 'price', 'location', 'latitude', 'longitude'].forEach((name) => {
            field(name).value = listing?.[name] ?? '';
        });

        const hasImage = Boolean(listing?.image_url);
        preview.src = hasImage ? listing.image_url : '';
        preview.classList.toggle('d-none', !hasImage);
        placeholder.classList.toggle('d-none', hasImage);
    });

    if (form.dataset.keep === '1') {
        window.bootstrap?.Modal.getOrCreateInstance(modal).show();
    }
}
