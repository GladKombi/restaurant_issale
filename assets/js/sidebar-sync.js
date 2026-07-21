(function () {
    const sidebar = document.querySelector('.sidebar, .admin-sidebar');
    if (!sidebar) return;
    const nav = sidebar.querySelector('nav');
    if (!nav) return;

    const current = location.pathname.split('/').pop() || 'dashboard.php';
    const modern = sidebar.classList.contains('admin-sidebar');
    // Preserve the link class used by each view. utilisateurs.php and the
    // Bootstrap dashboard use .nav-link, while the compact views use .nav.
    const linkClass = nav.querySelector('.nav-link') ? 'nav-link' : 'nav';
    const activeClass = `${linkClass} active`;
    const roleText = (sidebar.dataset.role || sidebar.querySelector('.user span, .sidebar-user span')?.textContent || '').trim().toLowerCase();
    const role = roleText === 'admin' || roleText.includes('administr') ? 'admin'
        : roleText.includes('cuisinier') ? 'cuisinier'
        : roleText.includes('serveur') ? 'serveur'
        : roleText.includes('gestionnaire') ? 'gestionnaire' : '';

    const allLinks = [
        ['dashboard.php', 'bi-grid-1x2', 'Tableau de bord'],
        ['commandes.php', 'bi-receipt', 'Commandes'],
        ['paiements.php', 'bi-wallet2', 'Paiements'],
        ['stocks.php', 'bi-box-seam', 'Stocks'],
        ['menus.php', 'bi-journal-richtext', 'Menus'],
        ['categories.php', 'bi-tags', 'Catégories'],
        ['tables.php', 'bi-grid-3x3-gap', 'Tables'],
        ['qrcodes.php', 'bi-qr-code', 'QR Codes'],
        ['utilisateurs.php', 'bi-people', 'Utilisateurs']
    ];
    const allowedPages = {
        admin: allLinks.map(([href]) => href),
        gestionnaire: ['dashboard.php','commandes.php','paiements.php','stocks.php','menus.php','categories.php','tables.php','qrcodes.php'],
        cuisinier: ['dashboard.php','stocks.php','menus.php'],
        serveur: ['dashboard.php','commandes.php']
    };
    const pages = allowedPages[role] || ['dashboard.php'];
    const links = allLinks.filter(([href]) => pages.includes(href));

    const labelClass = linkClass === 'nav-link' ? 'nav-label' : 'label';
    nav.innerHTML = `${modern ? '' : `<div class="${labelClass}">Menu</div>`}${links.map(([href, icon, label]) =>
        `<a class="${href === current ? activeClass : linkClass}" href="${href}"><i class="bi ${icon}"></i>${label}</a>`
    ).join('')}`;
})();
