function animateButton(btn) {
    btn.classList.add('is-animated');
    setTimeout(() => btn.classList.remove('is-animated'), 300);
}

function showAuthModal() {
    if (document.getElementById('auth-reaction-modal')) return;

    const modal = document.createElement('div');
    modal.id = 'auth-reaction-modal';
    modal.innerHTML = `
        <div class="auth-modal-content">
            <h4>Note:</h4>
            <p>Only registered users can post reactions.</p>
            <div class="auth-modal-buttons">
                <a href="/login" class="auth-btn confirm">Sign in</a>
                <button class="auth-btn cancel" id="close-auth-modal">Cancel</button>
            </div>
        </div>`;

    const style = `
        #auth-reaction-modal {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); display: flex; align-items: center;
            justify-content: center; z-index: 1000; font-family: inherit;
            animation: fadeIn 0.2s ease;
        }
        .auth-modal-content {
            background: #2D2D2D; padding: 20px;
            max-width: 320px; width: 100%; text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .auth-modal-content h4 { margin: 0 0 10px 0; font-size: 18px; color: #fff; }
        .auth-modal-content p { font-size: 14px; color: #fff; margin-bottom: 20px; }
        .auth-modal-buttons { display: flex; gap: 10px; justify-content: center; }
        .auth-btn {
            padding: 8px 16px; border: none;
            font-size: 14px; cursor: pointer; text-decoration: none;
        }
        .auth-btn.confirm { background: #1F1F1F; color: #fff; }
        .auth-btn.confirm:hover { background: #0056b3; }
        .auth-btn.cancel { background: #e4e6eb; color: #333; }
        .auth-btn.cancel:hover { background: #d8dadf; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    `;

    const styleSheet = document.createElement('style');
    styleSheet.innerText = style;
    document.head.appendChild(styleSheet);
    document.body.appendChild(modal);

    const closeModal = () => modal.remove();
    document.getElementById('close-auth-modal').addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
}

// ← ГЛАВНОЕ ИЗМЕНЕНИЕ: логика вынесена в отдельную функцию
function attachReactionHandlers(root = document) {
    root.querySelectorAll('.reaction-group').forEach((group) => {
        // Если уже вешали обработчики — пропускаем
        // Это как наклейка "уже проверено" — не проверяем дважды
        if (group.dataset.reactionsAttached) return;
        group.dataset.reactionsAttached = 'true';

        group.querySelectorAll('.reaction-btn').forEach((button) => {
            button.addEventListener('click', async (event) => {
                event.preventDefault();

                const commentId = button.dataset.commentId;
                const reactionType = button.dataset.reaction || 'like';
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                const wasActive = button.classList.contains('active');

                animateButton(button);

                try {
                    const url = `/api/comments/${commentId}/reaction`;
                    const response = wasActive
                        ? await fetch(url, {
                            method: 'DELETE',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                        })
                        : await fetch(url, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({ reaction: reactionType }),
                        });

                    if (response.status === 401) {
                        showAuthModal();
                        return;
                    }

                    const data = await response.json().catch(() => null);

                    if (!response.ok) {
                        console.error('Ошибка сервера:', response.status, data);
                        return;
                    }

                    group.querySelectorAll('.reaction-btn').forEach((btn) => {
                        btn.classList.remove('active');
                    });

                    if (!wasActive) {
                        button.classList.add('active');
                    }

                } catch (error) {
                    console.error('Ошибка запроса:', error);
                }
            });
        });
    });
}

// При загрузке страницы — вешаем на все существующие комментарии
document.addEventListener('DOMContentLoaded', () => attachReactionHandlers());
