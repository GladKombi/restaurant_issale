(function () {
    const sidebar = document.querySelector('.sidebar, .admin-sidebar');
    if (!sidebar) return;
    const nav = sidebar.querySelector('nav');
    if (!nav) return;

    const current = location.pathname.split('/').pop() || 'dashboard.php';
    const modern = sidebar.classList.contains('admin-sidebar');
    const linkClass = modern ? 'nav-link' : 'nav';
    const activeClass = `${linkClass} active`;
    const roleText = sidebar.querySelector('.user span, .sidebar-user span')?.textContent.trim().toLowerCase() || '';
    const canManageUsers = Boolean(nav.querySelector('[href="utilisateurs.php"]')) || current === 'utilisateurs.php' || roleText.includes('admin');

    const links = [
        ['dashboard.php', 'bi-grid-1x2', 'Tableau de bord'],
        ['commandes.php', 'bi-receipt', 'Commandes'],
        ['paiements.php', 'bi-wallet2', 'Paiements'],
        ['menus.php', 'bi-journal-richtext', 'Menus'],
        ['categories.php', 'bi-tags', 'Catégories'],
        ['tables.php', 'bi-grid-3x3-gap', 'Tables'],
        ['qrcodes.php', 'bi-qr-code', 'QR Codes'],
        ...(canManageUsers ? [['utilisateurs.php', 'bi-people', 'Utilisateurs']] : [])
    ];

    const labelClass = modern ? '' : 'label';
    nav.innerHTML = `${modern ? '' : `<div class="${labelClass}">Menu</div>`}${links.map(([href, icon, label]) =>
        `<a class="${href === current ? activeClass : linkClass}" href="${href}"><i class="bi ${icon}"></i>${label}</a>`
    ).join('')}`;
})();
