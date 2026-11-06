export function setupMediaActions() {
    const likeButton = document.getElementById('likeBtn');
    const likeForm = document.getElementById('likeForm');
    const watchlistForm = document.getElementById('watchlistForm');

    async function toggle(button, url, kind) {
        if (!button || button.disabled) return;
        button.disabled = true;
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            });
            if (!response.ok) throw new Error('Não foi possível salvar. Tente novamente.');
            const data = await response.json();
            const active = data.added;
            button.classList.toggle(kind === 'like' ? 'liked' : 'watchlisted', active);
            button.setAttribute('aria-pressed', String(active));
            const icon = button.querySelector('svg');
            icon?.setAttribute('fill', active ? 'currentColor' : 'none');
            if (icon && !active) {
                icon.setAttribute('stroke', 'currentColor');
                icon.setAttribute('stroke-width', '2');
            }
            if (kind === 'like') {
                const count = document.getElementById('like-count-display') || document.querySelector('.like-count');
                if (count) count.textContent = count.id ? data.count : `${data.count} Likes`;
            }
        } catch (error) {
            alert(error.message);
        } finally {
            button.disabled = false;
        }
    }

    if (likeForm) {
        likeForm.addEventListener('submit', event => {
            event.preventDefault();
            toggle(likeButton, likeForm.action, 'like');
        });
    } else if (likeButton?.dataset.url) {
        likeButton.addEventListener('click', () => toggle(likeButton, likeButton.dataset.url, 'like'));
    }
    watchlistForm?.addEventListener('submit', event => {
        event.preventDefault();
        toggle(document.getElementById('watchlistBtn'), watchlistForm.action, 'watchlist');
    });
}
