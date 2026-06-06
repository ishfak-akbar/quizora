function createCustomSelect(container, options, placeholder, onChange) {
    let selectedValue = null;

    container.innerHTML = `
        <div class="custom-select">
            <div class="custom-select-trigger">
                <span>${placeholder}</span>
                <i class="ti ti-chevron-down custom-select-chevron"></i>
            </div>
            <div class="custom-select-dropdown">
                ${options.map(opt => `
                    <div class="custom-select-option" data-value="${opt.value}">
                        ${opt.label}
                        <i class="ti ti-check check-icon"></i>
                    </div>
                `).join('')}
            </div>
        </div>
    `;

    const cs = container.querySelector('.custom-select');
    const trigger = cs.querySelector('.custom-select-trigger');
    const triggerText = trigger.querySelector('span');
    const opts = cs.querySelectorAll('.custom-select-option');

    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        document.querySelectorAll('.custom-select.open').forEach(el => {
            if (el !== cs) el.classList.remove('open');
        });
        cs.classList.toggle('open');
    });

    opts.forEach(opt => {
        opt.addEventListener('click', () => {
            selectedValue = opt.dataset.value;

            triggerText.textContent = opt.textContent.trim().replace('', '').trim();
            triggerText.classList.add('selected');

            opts.forEach(o => o.classList.remove('selected'));
            opt.classList.add('selected');

            cs.classList.remove('open');

            if (onChange) onChange(selectedValue);
        });
    });

    document.addEventListener('click', () => cs.classList.remove('open'));
}