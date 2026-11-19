const form = document.querySelector('[data-order-form]');

if (form) {
    const items = form.querySelector('[data-order-items]');
    const template = form.querySelector('[data-order-template]');
    const add = form.querySelector('[data-order-add]');
    const total = form.querySelector('[data-order-total]');
    const money = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' });
    const indexes = new Set([...items.children].map((row) => row.dataset.index));
    let nextIndex = 0;

    function updateOrder() {
        const rows = [...items.querySelectorAll('[data-order-row]')];
        let totalCents = 0;

        rows.forEach((row, index) => {
            const product = row.querySelector('[data-order-product]');
            const quantity = row.querySelector('[data-order-quantity]');
            const option = product.selectedOptions[0];
            const selected = Boolean(product.value);
            const priceCents = Number(option?.dataset.priceCents ?? 0);
            const quantityValue = Number(quantity.value);
            const subtotal = Number.isInteger(quantityValue) && quantityValue > 0
                ? priceCents * quantityValue
                : 0;

            if (selected) {
                quantity.max = option.dataset.stock;
            } else {
                quantity.removeAttribute('max');
            }

            row.querySelector('[data-order-number]').textContent = index + 1;
            row.querySelector('[data-order-remove]').hidden = rows.length < 2;
            row.querySelector('[data-order-price]').textContent = selected ? money.format(priceCents / 100) : '—';
            row.querySelector('[data-order-subtotal]').textContent = selected ? money.format(subtotal / 100) : '—';
            totalCents += subtotal;
        });

        total.textContent = money.format(totalCents / 100);
    }

    add.hidden = false;
    add.addEventListener('click', () => {
        const fragment = template.content.cloneNode(true);

        while (indexes.has(String(nextIndex))) {
            nextIndex += 1;
        }

        fragment.querySelectorAll('[id], [name], [for]').forEach((element) => {
            for (const attribute of ['id', 'name', 'for']) {
                if (element.hasAttribute(attribute)) {
                    element.setAttribute(attribute, element.getAttribute(attribute).replace('__INDEX__', nextIndex));
                }
            }
        });

        const product = fragment.querySelector('[data-order-product]');
        items.append(fragment);
        indexes.add(String(nextIndex));
        nextIndex += 1;
        updateOrder();
        product.focus();
    });

    items.addEventListener('click', (event) => {
        const remove = event.target.closest('[data-order-remove]');

        if (remove && items.children.length > 1) {
            remove.closest('[data-order-row]').remove();
            updateOrder();
            add.focus();
        }
    });

    const submit = form.querySelector('[type="submit"]');

    form.addEventListener('submit', (event) => {
        if (submit.disabled) {
            event.preventDefault();
            return;
        }

        submit.disabled = true;
    });

    window.addEventListener('pageshow', () => {
        submit.disabled = false;
    });

    items.addEventListener('input', updateOrder);
    updateOrder();
}

document.querySelectorAll('form[data-order-cancel]').forEach((cancel) => {
    cancel.addEventListener('submit', (event) => {
        if (!window.confirm(cancel.dataset.orderCancel)) {
            event.preventDefault();
        }
    });
});
