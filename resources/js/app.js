import './bootstrap';

const menuToggle = document.querySelector('[data-menu-toggle]');
const mobileNav = document.querySelector('[data-mobile-nav]');

menuToggle?.addEventListener('click', () => {
    const open = mobileNav?.classList.toggle('is-open') ?? false;
    menuToggle.setAttribute('aria-expanded', String(open));
});

mobileNav?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
    mobileNav.classList.remove('is-open');
    menuToggle?.setAttribute('aria-expanded', 'false');
}));

const sidebar = document.querySelector('[data-sidebar]');
const sidebarBackdrop = document.querySelector('[data-sidebar-backdrop]');
const setSidebar = (open) => {
    sidebar?.classList.toggle('is-open', open);
    sidebarBackdrop?.classList.toggle('is-open', open);
    document.body.style.overflow = open ? 'hidden' : '';
};

document.querySelector('[data-sidebar-open]')?.addEventListener('click', () => setSidebar(true));
document.querySelector('[data-sidebar-close]')?.addEventListener('click', () => setSidebar(false));
sidebarBackdrop?.addEventListener('click', () => setSidebar(false));

document.querySelector('[data-password-toggle]')?.addEventListener('click', (event) => {
    const input = document.querySelector('[data-password]');
    if (!input) return;
    const reveal = input.type === 'password';
    input.type = reveal ? 'text' : 'password';
    event.currentTarget.setAttribute('aria-label', reveal ? 'Ocultar contraseña' : 'Mostrar contraseña');
});

const loginLock = document.querySelector('[data-login-lock]');

if (loginLock) {
    const title = loginLock.querySelector('[data-login-lock-title]');
    const message = loginLock.querySelector('[data-login-lock-message]');
    const count = loginLock.querySelector('[data-login-lock-count]');
    const unit = loginLock.querySelector('[data-login-lock-unit]');
    const form = document.querySelector('[data-login-form]');
    const submit = form.querySelector('[data-login-submit]');
    const submitLabel = submit.querySelector('[data-login-submit-label]');
    const expiresAt = Date.now() + Number(loginLock.dataset.loginLockSeconds) * 1000;

    const countdown = window.setInterval(() => {
        const remaining = Math.ceil((expiresAt - Date.now()) / 1000);

        if (remaining > 0) {
            count.textContent = String(remaining);
            unit.textContent = remaining === 1 ? 'segundo' : 'segundos';
            submitLabel.textContent = `Espera ${remaining} s`;
            return;
        }

        window.clearInterval(countdown);
        title.textContent = 'Ya puedes volver a intentar.';
        message.removeAttribute('role');
        message.removeAttribute('aria-live');
        message.textContent = 'El bloqueo temporal terminó.';
        loginLock.classList.replace('alert--error', 'alert--success');
        submit.disabled = false;
        submitLabel.textContent = 'Ingresar al panel';
    }, 1000);
}

document.querySelectorAll('[data-dismiss]').forEach((button) => {
    button.addEventListener('click', () => button.closest('[data-dismissible]')?.remove());
});

document.querySelector('[data-table-search]')?.addEventListener('input', (event) => {
    const query = event.target.value.trim().toLocaleLowerCase('es');
    document.querySelectorAll('[data-search-row]').forEach((row) => {
        row.hidden = !row.textContent.toLocaleLowerCase('es').includes(query);
    });
});

document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (form.dataset.confirmed === 'true') return;
        event.preventDefault();
        openConfirmation(form.dataset.confirm, () => {
            form.dataset.confirmed = 'true';
            form.requestSubmit();
        });
    });
});

function openConfirmation(message, onConfirm) {
    const dialog = document.createElement('dialog');
    dialog.className = 'confirm-dialog';
    dialog.innerHTML = `
        <div class="confirm-dialog__icon" aria-hidden="true">!</div>
        <h2>Confirma esta acción</h2>
        <p></p>
        <div class="confirm-dialog__actions">
            <button type="button" class="button button--ghost" data-cancel>Cancelar</button>
            <button type="button" class="button confirm-dialog__danger" data-accept>Eliminar</button>
        </div>`;
    dialog.querySelector('p').textContent = message || '¿Deseas continuar?';
    document.body.append(dialog);
    dialog.querySelector('[data-cancel]').addEventListener('click', () => dialog.close());
    dialog.querySelector('[data-accept]').addEventListener('click', () => {
        dialog.close();
        onConfirm();
    });
    dialog.addEventListener('close', () => dialog.remove());
    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) dialog.close();
    });
    dialog.showModal();
}
