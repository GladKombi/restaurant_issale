<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['is_logged_in'])) {
    header('Location: ../connexion.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';

function dashboardValue(string $sql, array $params = []): float
{
    $result = fetchOne($sql, $params);
    return $result ? (float) $result->total : 0;
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$activeProducts = (int) dashboardValue("SELECT COUNT(*) total FROM menus WHERE supprimer = 0 AND is_available = 1");
$productsThisMonth = (int) dashboardValue("SELECT COUNT(*) total FROM menus WHERE supprimer = 0 AND created_at >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')");
$activeCategories = (int) dashboardValue("SELECT COUNT(*) total FROM categories WHERE supprimer = 0");
$todayOrders = (int) dashboardValue("SELECT COUNT(*) total FROM orders WHERE supprimer = 0 AND DATE(order_time) = CURRENT_DATE");
$pendingOrders = (int) dashboardValue("SELECT COUNT(*) total FROM orders WHERE supprimer = 0 AND status IN ('en attente', 'confirme', 'en preparation')");
$todayRevenue = dashboardValue("SELECT COALESCE(SUM(total_amount), 0) total FROM orders WHERE supprimer = 0 AND DATE(order_time) = CURRENT_DATE AND status <> 'annuler'");
$recentProducts = fetchAll("SELECT id, nom, price, is_available, created_at FROM menus WHERE supprimer = 0 ORDER BY created_at DESC, id DESC LIMIT 3");
$recentOrders = fetchAll("SELECT o.order_number, o.total_amount, o.status, o.order_type, o.order_time, t.number table_number FROM orders o LEFT JOIN tables t ON t.id = o.table_id WHERE o.supprimer = 0 ORDER BY o.order_time DESC, o.id DESC LIMIT 3");
$adminName = trim(($_SESSION['user_nom'] ?? 'Administrateur') . ' ' . ($_SESSION['user_prenom'] ?? ''));
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISSALE Restaurant - Administration</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* ============================================
                   ADMIN BASE STYLES - PALETTE ISSale RESTAURANT
                   ============================================ */
        :root {
            --sidebar-width: 260px;

            /* Couleurs principales - Ambiance chaleureuse */
            --primary: #8B1A1A;
            --primary-dark: #5C0E0E;
            --primary-light: #A83838;
            --secondary: #D4A373;
            --secondary-dark: #B8895E;
            --secondary-light: #F0D5B0;

            /* Neutres */
            --background: #FDF8F3;
            --surface: #FFFFFF;
            --surface-hover: #F8F0E8;
            --sidebar-bg: #1A120E;

            /* États */
            --success: #2D7D46;
            --warning: #D4A373;
            --danger: #C0392B;
            --info: #3498DB;

            /* Ombres */
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 20px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 8px 40px rgba(0, 0, 0, 0.12);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            padding-top: 0;
        }

        /* ============================================
                   SIDEBAR
                   ============================================ */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: rgba(255, 255, 255, 0.8);
            z-index: 1030;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .admin-sidebar .brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .admin-sidebar .brand h3 {
            font-family: 'Playfair Display', serif;
            color: white;
            margin: 0;
        }

        .admin-sidebar .brand small {
            color: var(--secondary);
            font-size: 0.7rem;
            letter-spacing: 2px;
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.6);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 12px;
            transition: all 0.3s ease;
        }

        .admin-sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
        }

        .admin-sidebar .nav-link.active {
            background: var(--secondary);
            color: #1A120E;
            font-weight: 500;
        }

        .admin-sidebar .nav-link i {
            width: 24px;
            margin-right: 12px;
        }

        /* ============================================
                   MAIN CONTENT
                   ============================================ */
        .admin-main {
            margin-left: var(--sidebar-width);
            padding: 24px;
            min-height: 100vh;
        }

        /* ============================================
                   TOP NAVBAR
                   ============================================ */
        .admin-topbar {
            background: white;
            border-radius: 12px;
            padding: 16px 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-topbar .page-title h4 {
            font-family: 'Playfair Display', serif;
            margin: 0;
            color: #1A120E;
        }

        .admin-topbar .page-title small {
            color: #6c757d;
            font-size: 0.85rem;
        }

        /* ============================================
                   STATS CARDS - AVEC COULEURS ADAPTÉES
                   ============================================ */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            border-radius: 4px 0 0 4px;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        /* Couleurs des cartes statistiques */
        .stat-card.primary-card::before {
            background: var(--primary);
        }

        .stat-card.secondary-card::before {
            background: var(--secondary);
        }

        .stat-card.success-card::before {
            background: var(--success);
        }

        .stat-card.warning-card::before {
            background: var(--warning);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-card .stat-icon.primary {
            background: rgba(139, 26, 26, 0.10);
            color: var(--primary);
        }

        .stat-card .stat-icon.secondary {
            background: rgba(212, 163, 115, 0.15);
            color: var(--secondary-dark);
        }

        .stat-card .stat-icon.success {
            background: rgba(45, 125, 70, 0.10);
            color: var(--success);
        }

        .stat-card .stat-icon.warning {
            background: rgba(212, 163, 115, 0.15);
            color: var(--warning);
        }

        .stat-card .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1.2;
            color: #1A120E;
        }

        .stat-card .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* ============================================
                   QUICK ACTIONS - BOUTONS PERSONNALISÉS
                   ============================================ */
        .quick-actions .btn {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .quick-actions .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: white;
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .btn-outline-secondary {
            color: var(--secondary-dark);
            border-color: var(--secondary);
        }

        .btn-outline-secondary:hover {
            background: var(--secondary);
            border-color: var(--secondary);
            color: #1A120E;
        }

        /* ============================================
                   RECENT ACTIVITY
                   ============================================ */
        .activity-item {
            display: flex;
            align-items: start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f0ebe5;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.9rem;
        }

        .activity-item .activity-icon.add {
            background: rgba(45, 125, 70, 0.10);
            color: var(--success);
        }

        .activity-item .activity-icon.edit {
            background: rgba(52, 152, 219, 0.10);
            color: var(--info);
        }

        .activity-item .activity-icon.delete {
            background: rgba(192, 57, 43, 0.10);
            color: var(--danger);
        }

        .activity-item .activity-icon.primary {
            background: rgba(139, 26, 26, 0.10);
            color: var(--primary);
        }

        .activity-item .activity-icon.warning {
            background: rgba(212, 163, 115, 0.15);
            color: var(--secondary-dark);
        }

        .activity-item .activity-icon.success {
            background: rgba(45, 125, 70, 0.10);
            color: var(--success);
        }

        /* ============================================
                   CARDS
                   ============================================ */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .card .card-header {
            background: white;
            border-bottom: 1px solid #f0ebe5;
            padding: 16px 20px 12px;
        }

        .card .card-header h6 {
            font-family: 'Playfair Display', serif;
            color: #1A120E;
        }

        .card .card-body {
            padding: 16px 20px;
        }

        /* ============================================
                   BADGES PERSONNALISÉS
                   ============================================ */
        .badge-primary {
            background: var(--primary);
            color: white;
        }

        .badge-secondary {
            background: var(--secondary);
            color: #1A120E;
        }

        .badge-success {
            background: var(--success);
            color: white;
        }

        .badge-warning {
            background: var(--warning);
            color: #1A120E;
        }

        /* ============================================
                   RESPONSIVE
                   ============================================ */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.open {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
                padding: 16px;
            }

            .admin-topbar {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .stat-card .stat-number {
                font-size: 1.4rem;
            }
        }

        /* ============================================
                   MOBILE MENU TOGGLE
                   ============================================ */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary);
            padding: 8px;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
        }

        /* ============================================
                   OVERLAY
                   ============================================ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1029;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* ============================================
                   MODAL STYLES
                   ============================================ */
        .modal .modal-header {
            border-bottom: 1px solid #f0ebe5;
            padding: 20px 24px 16px;
        }

        .modal .modal-header .modal-title {
            font-family: 'Playfair Display', serif;
            color: #1A120E;
        }

        .modal .modal-body {
            padding: 20px 24px;
        }

        .modal .modal-footer {
            border-top: 1px solid #f0ebe5;
            padding: 16px 24px 20px;
        }

        .modal .form-control,
        .modal .form-select {
            border-radius: 10px;
            border: 2px solid #e5ddd5;
            padding: 10px 16px;
            transition: all 0.3s ease;
        }

        .modal .form-control:focus,
        .modal .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(139, 26, 26, 0.10);
        }

        .modal .form-label {
            font-weight: 500;
            color: #1A120E;
        }

        .modal .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .modal .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .modal .btn-light {
            background: #F8F5F0;
            border-color: #e5ddd5;
            color: #1A120E;
        }

        .modal .btn-light:hover {
            background: #f0ebe5;
        }

        /* ============================================
                   TOAST STYLES
                   ============================================ */
        .toast-container .toast {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }

        .toast-container .toast .toast-body {
            padding: 12px 16px;
            font-weight: 500;
        }
        /* ============================================
           STYLE MODERNE DU MODÈLE FOURNI
           ============================================ */
        :root {
            --sidebar-width: 256px;
            --primary: #8B1A1A;
            --primary-dark: #5C0E0E;
            --primary-light: #A83838;
            --secondary: #D4A373;
            --background: #FDF8F3;
            --surface: #ffffff;
            --surface-hover: #F8F0E8;
            --sidebar-bg: #1A120E;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --border: #eadfd5;
            --text-main: #1A120E;
            --text-secondary: #6f625b;
            --text-muted: #9a8b82;
            --shadow-sm: 0 1px 2px rgb(0 0 0 / 5%);
            --shadow-md: 0 4px 12px rgb(15 23 42 / 8%);
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--text-main);
            background: var(--background);
            -webkit-font-smoothing: antialiased;
        }

        .font-playfair,
        .admin-topbar .page-title h4,
        .admin-sidebar .brand h3 {
            font-family: 'Outfit', sans-serif;
        }

        .admin-sidebar {
            width: var(--sidebar-width);
            padding: 24px;
            background: var(--sidebar-bg);
            color: rgba(255, 255, 255, .72);
            border-right: 1px solid var(--border);
            box-shadow: none;
        }

        .admin-sidebar .brand {
            padding: 0 0 30px;
            border-bottom: 0;
        }

        .admin-sidebar .brand h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-size: 1.15rem;
        }

        .admin-sidebar .brand h3::before {
            content: '\\F4D7';
            font-family: 'bootstrap-icons';
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 12px;
            box-shadow: 0 4px 10px rgb(139 26 26 / 30%);
        }

        .admin-sidebar .brand small {
            margin-left: 52px;
            color: var(--secondary);
            letter-spacing: 0;
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, .62);
            margin: 3px 0;
            padding: 10px 12px;
            border-radius: 12px;
            font-size: .875rem;
        }

        .admin-sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, .08);
        }

        .admin-sidebar .nav-link.active {
            color: #1A120E;
            background: var(--secondary);
            box-shadow: 0 2px 6px rgb(212 163 115 / 28%);
        }

        .admin-sidebar .position-absolute {
            left: 24px;
            bottom: 20px !important;
            width: calc(100% - 48px) !important;
            padding: 14px 0 0 !important;
            border-color: var(--border) !important;
        }

        .admin-main {
            margin-left: var(--sidebar-width);
            padding: 0 32px 32px;
        }

        .admin-topbar {
            position: sticky;
            top: 0;
            z-index: 1020;
            margin: 0 -32px 30px;
            padding: 17px 32px;
            border-radius: 0;
            border-bottom: 1px solid var(--border);
            background: rgb(253 248 243 / 88%);
            backdrop-filter: blur(12px);
            box-shadow: none;
        }

        .admin-topbar .page-title h4 {
            font-size: 1.5rem;
            color: var(--text-main);
        }

        .stat-card,
        .card,
        .quick-actions {
            border: 1px solid var(--border);
            border-radius: 20px;
            background: var(--surface);
            box-shadow: none;
        }

        .stat-card {
            padding: 24px;
        }

        .stat-card::before {
            display: none;
        }

        .stat-card:hover,
        .card:hover {
            transform: translateY(-2px);
            border-color: #dfc2a9;
            box-shadow: var(--shadow-md);
        }

        .stat-card .stat-label {
            text-transform: uppercase;
            letter-spacing: .05em;
            font-size: .72rem;
            color: var(--text-muted);
        }

        .stat-card .stat-number {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            color: var(--text-main);
        }

        .stat-card .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            box-shadow: none;
        }

        .stat-card .stat-icon.primary { background: #f8eaea; color: var(--primary); }
        .stat-card .stat-icon.secondary { background: #eff6ff; color: var(--info); }
        .stat-card .stat-icon.success { background: #ecfdf5; color: var(--success); }
        .stat-card .stat-icon.warning { background: #fffbeb; color: var(--warning); }

        .quick-actions {
            padding: 18px;
        }

        .btn-primary,
        .btn-primary-custom {
            border-color: var(--primary);
            background: var(--primary);
        }

        .btn-primary:hover,
        .btn-primary-custom:hover {
            border-color: var(--primary-dark);
            background: var(--primary-dark);
        }

        .card-header {
            padding: 22px 24px 10px;
        }

        .card-body {
            padding: 12px 24px 24px;
        }

        .activity-item {
            padding: 15px 0;
            border-color: var(--border);
        }

        .activity-icon {
            border-radius: 12px;
        }

        a { color: var(--primary); }

        @media (max-width: 768px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; padding: 0 18px 24px; }
            .admin-topbar { margin: 0 -18px 22px; padding: 14px 18px; }
            .stat-card { padding: 18px; }
        }
    </style>
</head>

<body>

    <!-- ============================================
SIDEBAR
============================================ -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="brand">
            <h3>ISSALE</h3>
            <small>Administration</small>
        </div>

        <nav class="nav flex-column mt-3">
            <a href="dashboard.php" class="nav-link active">
                <i class="bi bi-grid-1x2-fill"></i>Tableau de bord
            </a>
            <a href="menus.php" class="nav-link">
                <i class="bi bi-journal-richtext"></i>Menus
            </a>
            <a href="categories.php" class="nav-link">
                <i class="bi bi-tags"></i>Catégories
            </a>
            <a href="tables.php" class="nav-link">
                <i class="bi bi-grid-3x3-gap"></i>Tables
            </a>
            <a href="qrcodes.php" class="nav-link">
                <i class="bi bi-qr-code"></i>QR Codes
            </a>
            <?php if (($_SESSION['user_type'] ?? '') === 'admin'): ?>
            <a href="utilisateurs.php" class="nav-link">
                <i class="bi bi-people"></i>Utilisateurs
            </a>
            <?php endif; ?>
            <a href="commandes.php" class="nav-link">
                <i class="bi bi-clipboard-check"></i>Commandes
                <?php if ($pendingOrders > 0): ?><span class="badge bg-warning text-dark float-end mt-1"><?= $pendingOrders ?></span><?php endif; ?>
            </a>
            <a href="paiements.php" class="nav-link">
                <i class="bi bi-wallet2"></i>Paiements
            </a>
        </nav>

        <div class="position-absolute bottom-0 w-100 p-3" style="border-top: 1px solid rgba(255,255,255,0.08);">
            <a href="../index.php" class="nav-link" target="_blank">
                <i class="bi bi-eye"></i>Voir le site
            </a>
            <a href="../models/logout.php" class="nav-link text-danger">
                <i class="bi bi-box-arrow-right"></i>Déconnexion
            </a>
        </div>
    </aside>

    <!-- Overlay mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- ============================================
MAIN CONTENT
============================================ -->
    <main class="admin-main">

        <!-- Top Bar -->
        <div class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div class="page-title">
                    <h4>Tableau de bord</h4>
                    <small>Vue d'ensemble du restaurant</small>
                </div>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-light text-dark p-2">
                    <i class="bi bi-clock"></i> <span id="currentTime"></span>
                </span>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?= e($adminName) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="../models/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- ============================================
    STATS CARDS - AVEC COULEURS ADAPTÉES
    ============================================ -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card primary-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label">Produits actifs</div>
                            <div class="stat-number"><?= $activeProducts ?></div>
                        </div>
                        <div class="stat-icon primary">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                    <small class="text-success">
                        <i class="bi bi-arrow-up"></i> +<?= $productsThisMonth ?> ce mois
                    </small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card secondary-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label">Catégories</div>
                            <div class="stat-number"><?= $activeCategories ?></div>
                        </div>
                        <div class="stat-icon secondary">
                            <i class="bi bi-tags"></i>
                        </div>
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-check-circle"></i> Toutes actives
                    </small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card success-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label">Commandes du jour</div>
                            <div class="stat-number"><?= $todayOrders ?></div>
                        </div>
                        <div class="stat-icon success">
                            <i class="bi bi-bag-check"></i>
                        </div>
                    </div>
                    <small class="text-warning">
                        <i class="bi bi-clock"></i> <?= $pendingOrders ?> en cours
                    </small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card warning-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label">Chiffre d'affaires</div>
                            <div class="stat-number"><?= number_format($todayRevenue, 0, ',', ' ') ?> <small style="font-size:.45em">FC</small></div>
                        </div>
                        <div class="stat-icon warning">
                            <i class="bi bi-coin"></i>
                        </div>
                    </div>
                    <small class="text-success">
                        <i class="bi bi-calendar-check"></i> Aujourd'hui
                    </small>
                </div>
            </div>
        </div>

        <!-- ============================================
    QUICK ACTIONS - BOUTONS PERSONNALISÉS
    ============================================ -->
        <div class="quick-actions mb-4">
            <div class="d-flex flex-wrap gap-2">
                <a href="menus.php" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Ajouter un produit
                </a>
                <a href="categories.php#add" class="btn btn-outline-secondary">
                    <i class="bi bi-plus-lg me-2"></i>Ajouter une catégorie
                </a>
                <a href="commandes.php" class="btn btn-outline-primary">
                    <i class="bi bi-clipboard me-2"></i>Voir les commandes
                </a>
            </div>
        </div>

        <!-- ============================================
    RECENT ACTIVITY & QUICK PREVIEW
    ============================================ -->
        <div class="row g-4">
            <!-- Derniers produits ajoutés -->
            <div class="col-lg-6" id="recent-orders">
                <div class="card">
                    <div class="card-header bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">Derniers produits ajoutés</h6>
                            <a href="products.php" class="text-decoration-none small" style="color: var(--primary);">Voir tout</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!$recentProducts): ?>
                            <p class="text-muted text-center my-4">Aucun produit enregistré.</p>
                        <?php endif; ?>
                        <?php foreach ($recentProducts as $product): ?>
                            <div class="activity-item">
                                <div class="activity-icon add"><i class="bi bi-plus"></i></div>
                                <div>
                                    <strong><?= e($product->nom) ?></strong>
                                    <span class="badge <?= $product->is_available ? 'badge-success' : 'badge-secondary' ?> ms-2"><?= $product->is_available ? 'Actif' : 'Inactif' ?></span>
                                    <div class="small text-muted">Ajouté le <?= date('d/m/Y à H:i', strtotime($product->created_at)) ?></div>
                                </div>
                                <span class="ms-auto fw-bold" style="color: var(--primary);"><?= number_format((float) $product->price, 0, ',', ' ') ?> FC</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Commandes récentes -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">Commandes récentes</h6>
                            <a href="commandes.php" class="text-decoration-none small" style="color: var(--primary);">Voir tout</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!$recentOrders): ?>
                            <p class="text-muted text-center my-4">Aucune commande enregistrée.</p>
                        <?php endif; ?>
                        <?php foreach ($recentOrders as $order): ?>
                            <?php $isDone = in_array($order->status, ['servis', 'payer'], true); ?>
                            <div class="activity-item">
                                <div class="activity-icon <?= $isDone ? 'success' : 'warning' ?>"><i class="bi <?= $isDone ? 'bi-check-circle' : 'bi-clock' ?>"></i></div>
                                <div>
                                    <strong><?= e($order->order_number) ?></strong>
                                    <span class="badge <?= $isDone ? 'badge-success' : 'badge-warning' ?> ms-2"><?= e(ucfirst($order->status)) ?></span>
                                    <div class="small text-muted"><?= $order->table_number ? 'Table ' . e($order->table_number) . ' • ' : '' ?><?= e(ucfirst($order->order_type)) ?></div>
                                </div>
                                <span class="ms-auto fw-bold" style="color: var(--primary);"><?= number_format((float) $order->total_amount, 0, ',', ' ') ?> FC</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- ============================================
MODALS
============================================ -->

    <!-- Modal Ajouter Produit -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2" style="color: var(--primary);"></i>Ajouter un produit
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        <div class="row g-3">
                            <!-- Nom -->
                            <div class="col-md-6">
                                <label for="productName" class="form-label fw-semibold">Nom du produit *</label>
                                <input type="text" class="form-control" id="productName" placeholder="Ex: Burger Royal" required>
                            </div>

                            <!-- Prix -->
                            <div class="col-md-6">
                                <label for="productPrice" class="form-label fw-semibold">Prix (FC) *</label>
                                <input type="number" class="form-control" id="productPrice" placeholder="5000" required min="1">
                            </div>

                            <!-- Catégorie -->
                            <div class="col-md-6">
                                <label for="productCategory" class="form-label fw-semibold">Catégorie *</label>
                                <select class="form-select" id="productCategory" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="1">Entrées</option>
                                    <option value="2" selected>Plats</option>
                                    <option value="3">Pizzas</option>
                                    <option value="4">Grillades</option>
                                    <option value="5">Desserts</option>
                                    <option value="6">Boissons</option>
                                    <option value="7">Cocktails</option>
                                </select>
                            </div>

                            <!-- Temps préparation -->
                            <div class="col-md-6">
                                <label for="productPrepTime" class="form-label fw-semibold">Temps préparation (min)</label>
                                <input type="number" class="form-control" id="productPrepTime" placeholder="15" value="15" min="1">
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label for="productDescription" class="form-label fw-semibold">Description</label>
                                <textarea class="form-control" id="productDescription" rows="2" placeholder="Description du produit..."></textarea>
                            </div>

                            <!-- Tags & Allergènes -->
                            <div class="col-md-6">
                                <label for="productTags" class="form-label fw-semibold">Tags</label>
                                <select class="form-select" id="productTags" multiple size="2">
                                    <option value="nouveau">✨ Nouveau</option>
                                    <option value="populaire">⭐ Populaire</option>
                                    <option value="épicé">🌶️ Épicé</option>
                                </select>
                                <small class="text-muted">Maintenez Ctrl/Cmd pour sélectionner plusieurs</small>
                            </div>

                            <div class="col-md-6">
                                <label for="productAllergens" class="form-label fw-semibold">Allergènes</label>
                                <select class="form-select" id="productAllergens" multiple size="2">
                                    <option value="gluten">🌾 Gluten</option>
                                    <option value="lait">🥛 Lait</option>
                                    <option value="oeuf">🥚 Œuf</option>
                                    <option value="fruits-mer">🦐 Fruits de mer</option>
                                </select>
                                <small class="text-muted">Maintenez Ctrl/Cmd pour sélectionner plusieurs</small>
                            </div>

                            <!-- Image -->
                            <div class="col-12">
                                <label for="productImage" class="form-label fw-semibold">Photo</label>
                                <input type="file" class="form-control" id="productImage" accept="image/*">
                                <div id="imagePreview" class="mt-2"></div>
                            </div>

                            <!-- Statut -->
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="productActive" checked>
                                    <label class="form-check-label fw-semibold" for="productActive">Afficher sur le site client</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="saveProduct()">
                        <i class="bi bi-check2 me-2"></i>Ajouter le produit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
        <div id="adminToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    Action effectuée avec succès !
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ============================================
        // ADMIN JAVASCRIPT
        // ============================================

        // Horloge
        function updateClock() {
            const now = new Date();
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('fr-FR');
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }

        // Fermer sidebar sur click externe
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('adminSidebar');
            const toggle = document.querySelector('.menu-toggle');
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                    document.getElementById('sidebarOverlay').classList.remove('show');
                }
            }
        });

        // ============================================
        // TOAST SYSTEM
        // ============================================
        function showToast(message, type = 'success') {
            const toast = document.getElementById('adminToast');
            const toastBody = document.getElementById('toastMessage');
            const icon = type === 'success' ? 'bi-check-circle-fill text-success' :
                type === 'error' ? 'bi-x-circle-fill text-danger' :
                'bi-info-circle-fill text-info';

            toastBody.innerHTML = `<i class="bi ${icon} me-2"></i>${message}`;
            const bsToast = new bootstrap.Toast(toast, {
                delay: 4000
            });
            bsToast.show();
        }

        // ============================================
        // PRODUCT MANAGEMENT
        // ============================================
        function openAddProduct() {
            const modal = new bootstrap.Modal(document.getElementById('addProductModal'));
            modal.show();
        }

        function saveProduct() {
            // Simulation de sauvegarde
            const name = document.getElementById('productName').value;
            if (!name) {
                showToast('Veuillez remplir tous les champs obligatoires', 'error');
                return;
            }

            // Simuler un chargement
            const btn = document.querySelector('#addProductModal .btn-primary');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Ajout en cours...';

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check2 me-2"></i>Ajouter le produit';
                const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
                modal.hide();
                showToast('Produit ajouté avec succès !');
                document.getElementById('addProductForm').reset();
                document.getElementById('imagePreview').innerHTML = '';
            }, 1500);
        }

        // ============================================
        // IMAGE PREVIEW
        // ============================================
        document.getElementById('productImage')?.addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    img.className = 'img-thumbnail';
                    img.style.maxHeight = '150px';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // ============================================
        // LOGOUT CONFIRMATION
        // ============================================
        function confirmLogout() {
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                showToast('Déconnexion réussie');
            }
        }

        // ============================================
        // DEMO DATA - Pour les tableaux de bord
        // ============================================
        window.demoData = {
            products: [{
                    id: 1,
                    name: 'Burger Royal',
                    category: 'Plats',
                    price: 5000,
                    active: true,
                    tags: ['populaire', 'épicé']
                },
                {
                    id: 2,
                    name: 'Salade César',
                    category: 'Entrées',
                    price: 4500,
                    active: true,
                    tags: ['nouveau']
                },
                {
                    id: 3,
                    name: 'Pizza Margherita',
                    category: 'Pizzas',
                    price: 6000,
                    active: true,
                    tags: ['populaire']
                },
                {
                    id: 4,
                    name: 'Boeuf Bourguignon',
                    category: 'Plats',
                    price: 8500,
                    active: true,
                    tags: ['populaire']
                },
                {
                    id: 5,
                    name: 'Tarte Tatin',
                    category: 'Desserts',
                    price: 3500,
                    active: false,
                    tags: []
                },
                {
                    id: 6,
                    name: 'Jus de Fruit Frais',
                    category: 'Boissons',
                    price: 2000,
                    active: true,
                    tags: []
                },
                {
                    id: 7,
                    name: 'Brochettes de Poulet',
                    category: 'Grillades',
                    price: 5500,
                    active: true,
                    tags: ['épicé']
                },
                {
                    id: 8,
                    name: 'Mojito Classique',
                    category: 'Cocktails',
                    price: 4000,
                    active: true,
                    tags: []
                },
                {
                    id: 9,
                    name: 'Salade Niçoise',
                    category: 'Entrées',
                    price: 4800,
                    active: true,
                    tags: ['nouveau']
                },
                {
                    id: 10,
                    name: 'Pizza Reine',
                    category: 'Pizzas',
                    price: 6500,
                    active: true,
                    tags: []
                },
                {
                    id: 11,
                    name: 'Tiramisu',
                    category: 'Desserts',
                    price: 3800,
                    active: true,
                    tags: ['populaire']
                },
                {
                    id: 12,
                    name: 'Café Gourmand',
                    category: 'Boissons',
                    price: 2500,
                    active: false,
                    tags: []
                }
            ],
            categories: [{
                    id: 1,
                    name: 'Entrées',
                    order: 1,
                    active: true
                },
                {
                    id: 2,
                    name: 'Plats',
                    order: 2,
                    active: true
                },
                {
                    id: 3,
                    name: 'Pizzas',
                    order: 3,
                    active: true
                },
                {
                    id: 4,
                    name: 'Grillades',
                    order: 4,
                    active: true
                },
                {
                    id: 5,
                    name: 'Desserts',
                    order: 5,
                    active: true
                },
                {
                    id: 6,
                    name: 'Boissons',
                    order: 6,
                    active: true
                },
                {
                    id: 7,
                    name: 'Cocktails',
                    order: 7,
                    active: false
                }
            ]
        };
    </script>

<script src="../assets/js/sidebar-sync.js"></script>
</body>

</html>
