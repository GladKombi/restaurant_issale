<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$loginError = $_SESSION['login_error'] ?? '';
$loginEmailValue = $_SESSION['login_email'] ?? '';
unset($_SESSION['login_error'], $_SESSION['login_email']);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISSALE Restaurant - Connexion</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* ============================================
                   LOGIN PAGE STYLES
                   ============================================ */
        :root {
            --primary: #8B1A1A;
            --primary-dark: #5C0E0E;
            --secondary: #D4A373;
            --light-bg: #FDF8F3;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* ============================================
                   LOGIN CONTAINER
                   ============================================ */
        .login-wrapper {
            width: 100%;
            max-width: 1200px;
            display: flex;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            min-height: 600px;
        }

        /* ============================================
                   LEFT SIDE - BRANDING
                   ============================================ */
        .login-branding {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-branding::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            transform: rotate(45deg);
        }

        .login-branding .brand-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            color: var(--secondary);
        }

        .login-branding h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .login-branding .brand-sub {
            font-size: 0.8rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 24px;
            position: relative;
            z-index: 1;
        }

        .login-branding p {
            opacity: 0.9;
            font-size: 1.05rem;
            line-height: 1.6;
            max-width: 400px;
            position: relative;
            z-index: 1;
        }

        .login-branding .features {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .login-branding .features .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            opacity: 0.9;
        }

        .login-branding .features .feature-item i {
            color: var(--secondary);
            font-size: 1.2rem;
        }

        /* ============================================
                   RIGHT SIDE - LOGIN FORM
                   ============================================ */
        .login-form-wrapper {
            flex: 1;
            padding: 60px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }

        .login-form-wrapper .form-header {
            margin-bottom: 32px;
        }

        .login-form-wrapper .form-header h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: #1A120E;
            margin-bottom: 8px;
        }

        .login-form-wrapper .form-header p {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .login-form-wrapper .form-header .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(139, 26, 26, 0.08);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: var(--primary);
            font-weight: 500;
            margin-top: 8px;
        }

        /* ============================================
                   FORM STYLES
                   ============================================ */
        .login-form .form-group {
            margin-bottom: 20px;
        }

        .login-form .form-label {
            font-weight: 500;
            color: #1A120E;
            font-size: 0.9rem;
        }

        .login-form .input-group-custom {
            position: relative;
        }

        .login-form .input-group-custom .form-control {
            padding: 12px 16px 12px 44px;
            border-radius: 12px;
            border: 2px solid #e5ddd5;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            background: #FDF8F3;
        }

        .login-form .input-group-custom .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(139, 26, 26, 0.1);
            background: white;
        }

        .login-form .input-group-custom .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 10;
        }

        .login-form .input-group-custom .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 4px;
            z-index: 10;
        }

        .login-form .input-group-custom .toggle-password:hover {
            color: var(--primary);
        }

        /* ============================================
                   FORM OPTIONS
                   ============================================ */
        .login-form .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 16px 0 24px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .login-form .form-options .form-check {
            margin-bottom: 0;
        }

        .login-form .form-options .form-check-input {
            border-radius: 4px;
            border: 2px solid #d4c9bf;
            cursor: pointer;
        }

        .login-form .form-options .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .login-form .form-options .form-check-label {
            font-size: 0.9rem;
            color: #5A4A3A;
            cursor: pointer;
        }

        .login-form .form-options .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .login-form .form-options .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* ============================================
                   BUTTONS
                   ============================================ */
        .login-form .btn-login {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            background: var(--primary);
            color: white;
            transition: all 0.3s ease;
            position: relative;
        }

        .login-form .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(139, 26, 26, 0.25);
        }

        .login-form .btn-login:active {
            transform: translateY(0);
        }

        .login-form .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .login-form .btn-login .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto;
        }

        .login-form .btn-login.loading .btn-text {
            display: none;
        }

        .login-form .btn-login.loading .spinner {
            display: block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ============================================
                   DEMO CREDENTIALS
                   ============================================ */
        .demo-credentials {
            margin-top: 24px;
            padding: 16px 20px;
            background: #F8F5F0;
            border-radius: 12px;
            border: 1px dashed #d4c9bf;
        }

        .demo-credentials .demo-title {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #6c757d;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .demo-credentials .credential-item {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 0.85rem;
            color: #5A4A3A;
        }

        .demo-credentials .credential-item .label {
            font-weight: 500;
        }

        .demo-credentials .credential-item .value {
            font-family: 'Courier New', monospace;
            color: var(--primary);
            font-weight: 600;
        }

        /* ============================================
                   TOAST / ALERT
                   ============================================ */
        .alert-custom {
            border-radius: 12px;
            padding: 12px 16px;
            border: none;
            font-size: 0.9rem;
            display: none;
            align-items: center;
            gap: 10px;
        }

        .alert-custom.show {
            display: flex;
        }

        .alert-custom i {
            font-size: 1.2rem;
        }

        .alert-custom.alert-danger {
            background: #FEE2E2;
            color: #991B1B;
        }

        .alert-custom.alert-success {
            background: #D1FAE5;
            color: #065F46;
        }

        /* ============================================
                   RESPONSIVE
                   ============================================ */
        @media (max-width: 992px) {
            .login-wrapper {
                flex-direction: column;
                max-width: 480px;
                min-height: auto;
            }

            .login-branding {
                padding: 40px 32px;
                text-align: center;
            }

            .login-branding p {
                max-width: 100%;
            }

            .login-branding .features {
                align-items: center;
            }

            .login-form-wrapper {
                padding: 40px 32px;
            }
        }

        @media (max-width: 480px) {
            .login-branding {
                padding: 32px 20px;
            }

            .login-branding h1 {
                font-size: 2.2rem;
            }

            .login-form-wrapper {
                padding: 32px 20px;
            }

            .login-form .form-options {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

    <!-- ============================================
LOGIN WRAPPER
============================================ -->
    <div class="login-wrapper">

        <!-- ============================================
    LEFT SIDE - BRANDING
    ============================================ -->
        <div class="login-branding">
            <div class="brand-icon">
                <i class="bi bi-cup-hot"></i>
            </div>
            <h1>ISSALE</h1>
            <div class="brand-sub">Restaurant</div>
            <p>
                Connectez-vous pour gérer votre restaurant,
                vos menus et vos commandes en temps réel.
            </p>

            <div class="features">
                <div class="feature-item">
                    <i class="bi bi-box-seam"></i>
                    <span>Gestion complète du menu</span>
                </div>
                <div class="feature-item">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Suivi des commandes en direct</span>
                </div>
                <div class="feature-item">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Tableau de bord analytique</span>
                </div>
                <div class="feature-item">
                    <i class="bi bi-shield-lock"></i>
                    <span>Accès sécurisé et restreint</span>
                </div>
            </div>

            <!-- Version -->
            <div style="position: absolute; bottom: 24px; left: 40px; font-size: 0.7rem; opacity: 0.4; z-index: 1;">
                v1.0.0
            </div>
        </div>

        <!-- ============================================
    RIGHT SIDE - LOGIN FORM
    ============================================ -->
        <div class="login-form-wrapper">

            <!-- Header -->
            <div class="form-header">
                <h2>Connexion</h2>
                <p>Connectez-vous à votre espace d'administration</p>
                <div class="role-badge">
                    <i class="bi bi-shield-check"></i>
                    Accès administrateur / Gestionnaire
                </div>
            </div>

            <!-- Alert Message -->
            <div class="alert-custom alert-danger<?= $loginError ? ' show' : '' ?>" id="loginAlert">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span id="alertMessage"><?= htmlspecialchars($loginError ?: 'Email ou mot de passe incorrect', ENT_QUOTES, 'UTF-8') ?></span>
            </div>

            <div class="alert-custom alert-success" id="loginSuccess">
                <i class="bi bi-check-circle-fill"></i>
                <span>Connexion réussie ! Redirection en cours...</span>
            </div>

            <!-- Login Form -->
            <form class="login-form" id="loginForm" action="models/login.php" method="post">
                <input type="hidden" name="action" value="login">
                <!-- Email -->
                <div class="form-group">
                    <label for="loginEmail" class="form-label">Adresse email</label>
                    <div class="input-group-custom">
                        <i class="bi bi-envelope input-icon"></i>
                        <input
                            type="email"
                            class="form-control"
                            id="loginEmail"
                            name="email"
                            placeholder="admin@issale.com"
                            value="<?= htmlspecialchars($loginEmailValue, ENT_QUOTES, 'UTF-8') ?>"
                            required
                            autocomplete="email"
                            aria-label="Adresse email">
                    </div>
                    <div class="invalid-feedback" id="emailError">
                        Veuillez saisir une adresse email valide
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="loginPassword" class="form-label">Mot de passe</label>
                    <div class="input-group-custom">
                        <i class="bi bi-lock input-icon"></i>
                        <input
                            type="password"
                            class="form-control"
                            id="loginPassword"
                            name="password"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                            aria-label="Mot de passe"
                            minlength="4">
                        <button
                            type="button"
                            class="toggle-password"
                            id="togglePassword"
                            aria-label="Afficher le mot de passe">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <div class="invalid-feedback" id="passwordError">
                        Le mot de passe doit contenir au moins 4 caractères
                    </div>
                </div>

                <!-- Options -->
                <div class="form-options">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" value="1">
                        <label class="form-check-label" for="rememberMe">
                            Se souvenir de moi
                        </label>
                    </div>
                    <a href="#" class="forgot-link" id="forgotPassword">
                        Mot de passe oublié ?
                    </a>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn btn-login" id="loginBtn">
                    <span class="btn-text">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                    </span>
                    <div class="spinner"></div>
                </button>

                
            </form>

            <!-- Lien retour site client -->
            <div class="text-center mt-4">
                <a href="index.php" class="text-decoration-none" style="color: var(--primary); font-size: 0.9rem;">
                    <i class="bi bi-arrow-left me-1"></i>Retour au site client
                </a>
            </div>
        </div>
    </div>

    <!-- ============================================
TOAST NOTIFICATION (Optionnel)
============================================ -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
        <div id="loginToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    Action effectuée avec succès !
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- ============================================
FORGOT PASSWORD MODAL
============================================ -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-playfair">
                        <i class="bi bi-envelope-paper me-2 text-primary"></i>Réinitialisation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">
                        Saisissez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                    </p>
                    <div class="form-group">
                        <label for="resetEmail" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" id="resetEmail" placeholder="admin@issale.com">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="sendResetLink()">
                        <i class="bi bi-send me-2"></i>Envoyer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================
BOOTSTRAP JS
============================================ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ============================================
        // LOGIN JAVASCRIPT
        // ============================================

        // Éléments DOM
        const loginForm = document.getElementById('loginForm');
        const loginEmail = document.getElementById('loginEmail');
        const loginPassword = document.getElementById('loginPassword');
        const loginBtn = document.getElementById('loginBtn');
        const loginAlert = document.getElementById('loginAlert');
        const loginSuccess = document.getElementById('loginSuccess');
        const alertMessage = document.getElementById('alertMessage');
        const togglePassword = document.getElementById('togglePassword');
        const toggleIcon = document.getElementById('toggleIcon');

        // ============================================
        // TOGGLE PASSWORD VISIBILITY
        // ============================================
        togglePassword.addEventListener('click', function() {
            const type = loginPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            loginPassword.setAttribute('type', type);
            toggleIcon.className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
        });

        // ============================================
        // REAL-TIME VALIDATION CLEANUP
        // ============================================
        loginEmail.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            loginAlert.classList.remove('show');
        });

        loginPassword.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            loginAlert.classList.remove('show');
        });

        // ============================================
        // FORGOT PASSWORD
        // ============================================
        document.getElementById('forgotPassword').addEventListener('click', function(e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('forgotPasswordModal'));
            modal.show();
        });

        function sendResetLink() {
            const email = document.getElementById('resetEmail').value;
            if (!email) {
                showToast('Veuillez saisir votre adresse email', 'error');
                return;
            }

            showToast('Un lien de réinitialisation a été envoyé à ' + email, 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal'));
            modal.hide();
            document.getElementById('resetEmail').value = '';
        }

        // ============================================
        // TOAST SYSTEM
        // ============================================
        function showToast(message, type = 'success') {
            const toast = document.getElementById('loginToast');
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
        // PASSWORD STRENGTH INDICATOR (Optionnel)
        // ============================================
        loginPassword.addEventListener('input', function() {
            const val = this.value;
            const strength = document.getElementById('passwordStrength');

            // Créer l'indicateur si pas présent
            let indicator = document.querySelector('.password-strength');
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.className = 'password-strength mt-1';
                indicator.style.cssText = 'display: flex; gap: 4px; align-items: center; font-size: 0.75rem;';
                this.parentNode.appendChild(indicator);
            }

            if (val.length === 0) {
                indicator.innerHTML = '';
                return;
            }

            let strengthLevel = 0;
            if (val.length >= 6) strengthLevel++;
            if (val.length >= 10) strengthLevel++;
            if (/[A-Z]/.test(val) && /[a-z]/.test(val)) strengthLevel++;
            if (/\d/.test(val)) strengthLevel++;
            if (/[^A-Za-z0-9]/.test(val)) strengthLevel++;

            const levels = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];
            const colors = ['#dc3545', '#fd7e14', '#ffc107', '#20c997', '#198754'];
            const level = Math.min(strengthLevel, 4);

            indicator.innerHTML = `
        <span style="color: ${colors[level]};">●</span>
        <span style="color: #6c757d;">${levels[level]}</span>
    `;
        });
    </script>

</body>

</html>
