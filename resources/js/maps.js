/**
 * Leaflet maps: buyer location picker, seller listing location picker and
 * read-only product maps. Leaflet is fetched from the CDN on first use.
 */
const LEAFLET_VERSION = '1.9.4';
const DEFAULT_CENTER = [3.0698, 101.5037]; // UiTM Shah Alam
let leafletPromise;

export function loadLeaflet() {
    if (window.L) return Promise.resolve(window.L);

    leafletPromise ??= new Promise((resolve, reject) => {
        const css = document.createElement('link');
        css.rel = 'stylesheet';
        css.href = `https://unpkg.com/leaflet@${LEAFLET_VERSION}/dist/leaflet.css`;
        document.head.appendChild(css);

        const script = document.createElement('script');
        script.src = `https://unpkg.com/leaflet@${LEAFLET_VERSION}/dist/leaflet.js`;
        script.onload = () => resolve(window.L);
        script.onerror = reject;
        document.head.appendChild(script);
    });

    return leafletPromise;
}

function baseMap(L, el, center, zoom = 14) {
    const map = L.map(el, { scrollWheelZoom: false }).setView(center, zoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap',
    }).addTo(map);
    return map;
}

/** Best-effort area name for a coordinate. */
async function areaName(lat, lng) {
    try {
        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&zoom=14&lat=${lat}&lon=${lng}`, {
            headers: { Accept: 'application/json' },
        });
        const { address = {} } = await res.json();
        return address.suburb || address.city || address.town || address.village || address.county || address.state || 'Pinned location';
    } catch {
        return 'Pinned location';
    }
}

/**
 * A clickable map that writes lat / lng / area into form fields.
 *
 * @param {HTMLElement} el     map container
 * @param {object}      fields { lat, lng, area } input elements
 * @param {Function}    onPick optional callback(lat, lng, area)
 */
async function createPicker(el, fields, onPick) {
    const L = await loadLeaflet();
    const lat = parseFloat(fields.lat.value);
    const lng = parseFloat(fields.lng.value);
    const hasPoint = !Number.isNaN(lat) && !Number.isNaN(lng);

    const map = baseMap(L, el, hasPoint ? [lat, lng] : DEFAULT_CENTER, hasPoint ? 15 : 13);
    let marker = hasPoint ? L.marker([lat, lng]).addTo(map) : null;

    const pick = async (point) => {
        marker ? marker.setLatLng(point) : (marker = L.marker(point).addTo(map));
        fields.lat.value = point.lat.toFixed(7);
        fields.lng.value = point.lng.toFixed(7);
        if (fields.area) fields.area.value = 'Locating…';
        const area = await areaName(point.lat, point.lng);
        if (fields.area) fields.area.value = area;
        onPick?.(point.lat, point.lng, area);
    };

    map.on('click', (e) => pick(e.latlng));

    return {
        map,
        pick,
        setView(point, zoom = 15) {
            map.setView(point, zoom);
            marker ? marker.setLatLng(point) : (marker = L.marker(point).addTo(map));
        },
        reset() {
            marker?.remove();
            marker = null;
            map.setView(DEFAULT_CENTER, 13);
        },
    };
}

function useMyLocation(button, onFound) {
    button?.addEventListener('click', () => {
        if (!navigator.geolocation) return;
        const label = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Locating…';
        navigator.geolocation.getCurrentPosition(
            ({ coords }) => {
                onFound({ lat: coords.latitude, lng: coords.longitude });
                button.disabled = false;
                button.innerHTML = label;
            },
            () => {
                button.disabled = false;
                button.innerHTML = label;
                window.alert('We could not get your location. Tap the map instead.');
            },
            { enableHighAccuracy: true, timeout: 10000 },
        );
    });
}

/** Header "Set location" modal for buyers. */
export function initLocationModal() {
    const modal = document.getElementById('locationModal');
    if (!modal) return;

    const form = modal.querySelector('form[data-location-form]');
    const radius = form.querySelector('[name="radius"]');
    const radiusLabel = form.querySelector('[data-radius-label]');
    const submit = form.querySelector('button[type="submit"]');
    const fields = ['lat', 'lng', 'area'].reduce((acc, name) => ({ ...acc, [name]: form.elements.namedItem(name) }), {});
    let picker;

    radius.addEventListener('input', () => { radiusLabel.textContent = radius.value; });
    submit.disabled = !fields.lat.value;

    modal.addEventListener('shown.bs.modal', async () => {
        if (!picker) {
            picker = await createPicker(modal.querySelector('[data-map]'), fields, () => { submit.disabled = false; });
            useMyLocation(modal.querySelector('[data-my-location]'), (point) => {
                picker.setView(point);
                picker.pick(point);
            });
        }
        picker.map.invalidateSize();
    });
}

/**
 * Pickers declared in markup: <div data-map-picker data-form="#formId"></div>
 * The form must contain latitude / longitude / location inputs.
 * Exposed on window so the listing modal can re-centre after filling.
 */
export function initMapPickers() {
    document.querySelectorAll('[data-map-picker]').forEach((el) => {
        const form = el.closest('form');
        const field = (name) => form.elements.namedItem(name);
        const fields = { lat: field('latitude'), lng: field('longitude'), area: field('location') };
        const modal = el.closest('.modal');
        let picker;

        const ensure = async () => {
            if (!picker) {
                picker = await createPicker(el, fields);
                useMyLocation(form.querySelector('[data-my-location]'), (point) => {
                    picker.setView(point);
                    picker.pick(point);
                });
            }
            picker.map.invalidateSize();

            const lat = parseFloat(fields.lat.value);
            const lng = parseFloat(fields.lng.value);
            Number.isNaN(lat) || Number.isNaN(lng) ? picker.reset() : picker.setView({ lat, lng });
        };

        modal ? modal.addEventListener('shown.bs.modal', ensure) : ensure();
    });
}

/** Read-only map: <div data-static-map data-lat="" data-lng=""></div> */
export function initStaticMaps() {
    document.querySelectorAll('[data-static-map]').forEach(async (el) => {
        const L = await loadLeaflet();
        const point = [parseFloat(el.dataset.lat), parseFloat(el.dataset.lng)];
        const map = baseMap(L, el, point, 15);
        L.circle(point, { radius: 250, color: '#4b2a85', fillOpacity: 0.15 }).addTo(map);
        L.marker(point).addTo(map);
    });
}
