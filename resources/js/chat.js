import { escapeHtml, showToast } from './ui';

/**
 * Conversation thread: send, receive (Echo when available, otherwise
 * polling), and keep the view scrolled to the newest message.
 */
export function initChat() {
    const root = document.getElementById('chatThread');
    if (!root) return;

    const { conversation, me, sendUrl, pollUrl } = root.dataset;
    const list = root.querySelector('[data-messages]');
    const form = root.querySelector('[data-compose]');
    const input = form.querySelector('textarea');
    const button = form.querySelector('button');
    const seen = new Set([...list.querySelectorAll('[data-id]')].map((el) => Number(el.dataset.id)));
    let lastId = Math.max(0, ...seen);

    const scrollDown = () => { list.scrollTop = list.scrollHeight; };
    const time = (iso) => new Date(iso).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    const append = (msg) => {
        if (seen.has(msg.id)) return;
        seen.add(msg.id);
        lastId = Math.max(lastId, msg.id);
        list.querySelector('[data-empty]')?.remove();

        const mine = Number(msg.sender_id) === Number(me);
        list.insertAdjacentHTML('beforeend', `
            <div class="bubble-row ${mine ? 'me' : 'them'}" data-id="${msg.id}">
                <div class="bubble">${escapeHtml(msg.message)}<time>${time(msg.created_at)}</time></div>
            </div>`);
        scrollDown();
    };

    const send = async () => {
        const text = input.value.trim();
        if (!text) return;

        button.disabled = true;
        try {
            const { data } = await window.axios.post(sendUrl, { message: text });
            append(data.message);
            input.value = '';
            input.style.height = '';
        } catch (error) {
            showToast(error.response?.data?.message ?? 'Message not sent. Please try again.', 'error');
        } finally {
            button.disabled = false;
            input.focus();
        }
    };

    form.addEventListener('submit', (e) => { e.preventDefault(); send(); });
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(); }
    });
    input.addEventListener('input', () => {
        input.style.height = 'auto';
        input.style.height = `${Math.min(input.scrollHeight, 140)}px`;
    });

    const poll = async () => {
        try {
            const { data } = await window.axios.get(pollUrl, { params: { after: lastId } });
            data.messages.forEach(append);
        } catch { /* try again on the next tick */ }
    };

    if (window.Echo) {
        window.Echo.private(`chat.${conversation}`).listen('.message.sent', (e) => append(e.message));
    }
    // Polling is the fallback when realtime is unavailable, and a slow safety net otherwise.
    setInterval(poll, window.Echo ? 20000 : 4000);

    scrollDown();
    input.focus();
}
