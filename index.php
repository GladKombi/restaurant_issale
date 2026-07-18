<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISSALE Restaurant - Découvrez notre menu</title>
    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Styles personnalisés */
        :root {
            --primary: #8B1A1A;
            --secondary: #D4A373;
            --light-bg: #FDF8F3;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: #1A120E;
        }
        
        .font-playfair {
            font-family: 'Playfair Display', serif;
        }
        
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(139,26,26,0.95) 0%, rgba(92,14,14,0.98) 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"><circle cx="50" cy="50" r="40" fill="rgba(212,163,115,0.1)"/><circle cx="350" cy="300" r="60" fill="rgba(212,163,115,0.08)"/></svg>');
            background-size: 400px 400px;
        }
        
        .btn-primary-custom {
            background: var(--secondary);
            color: #1A120E;
            padding: 14px 40px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(212,163,115,0.3);
            background: #c9956a;
            color: #1A120E;
        }
        
        .btn-outline-light-custom {
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            padding: 14px 40px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-outline-light-custom:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }
        
        .category-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        
        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        .category-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 12px;
        }
        
        .step-card {
            text-align: center;
            padding: 30px 20px;
            position: relative;
        }
        
        .step-number {
            width: 60px;
            height: 60px;
            background: var(--secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 auto 16px;
            color: #1A120E;
        }
        
        .review-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--box-shadow-sm);
            border: none;
        }
        
        .rating-stars {
            color: var(--secondary);
        }
        
        /* Navbar */
        .navbar-custom {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary) !important;
        }
        
        .brand-sub {
            font-size: 0.7rem;
            display: block;
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            color: var(--secondary);
            letter-spacing: 2px;
        }
        
        /* Footer */
        .footer-custom {
            background: #1A120E;
            color: rgba(255,255,255,0.8);
            padding: 60px 0 30px;
        }
        
        .footer-custom h5 {
            color: white;
            font-family: 'Playfair Display', serif;
        }
        
        .social-link {
            color: rgba(255,255,255,0.6);
            transition: all 0.3s ease;
            font-size: 1.5rem;
        }
        
        .social-link:hover {
            color: var(--secondary);
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.html">
            ISSALE
            <span class="brand-sub">Restaurant</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link active" href="index.html">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="menu.html">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative" href="cart.html">
                        <i class="bi bi-cart3 fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount">
                            0
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="connexion.php" class="btn btn-outline-danger ms-lg-3 mt-2 mt-lg-0">
                        <i class="bi bi-person-circle me-2"></i>Se connecter
                    </a>
                </li>
                <li class="nav-item">
                    <a href="menu.html" class="btn btn-primary-custom ms-lg-3">
                        Commander
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section d-flex align-items-center">
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center text-white">
                <h1 class="display-1 font-playfair fw-bold mb-4" style="font-size: 4.5rem;">
                    ISSALE
                </h1>
                <p class="lead mb-4" style="font-size: 1.3rem; opacity: 0.9;">
                    Découvrez une expérience culinaire unique
                </p>
                <p class="mb-5" style="font-size: 1.1rem; opacity: 0.8;">
                    Scannez le QR code ou parcourez notre menu pour commander en quelques secondes
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="menu.html" class="btn btn-primary-custom btn-lg">
                        <i class="bi bi-menu-button-wide me-2"></i>Voir le menu
                    </a>
                    <button class="btn btn-outline-light-custom btn-lg" data-bs-toggle="modal" data-bs-target="#qrModal">
                        <i class="bi bi-qr-code me-2"></i>Scanner le QR
                    </button>
                </div>
                <div class="mt-5 d-flex justify-content-center gap-4">
                    <span><i class="bi bi-clock me-2"></i>Commande en temps réel</span>
                    <span><i class="bi bi-truck me-2"></i>Livraison rapide</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- QR Modal -->
<div class="modal fade" id="qrModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <i class="bi bi-qr-code" style="font-size: 8rem; color: var(--primary);"></i>
                <h4 class="mt-4">Scannez le QR Code</h4>
                <p class="text-muted">Placez votre téléphone devant le QR code pour accéder au menu</p>
                <button class="btn btn-primary-custom" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Catégories Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center font-playfair mb-5" style="font-size: 2.5rem;">
            Notre Menu
        </h2>
        <div class="row g-4">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="category-card">
                    <div class="category-icon"><i class="bi bi-egg-fried"></i></div>
                    <h6>Entrées</h6>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="category-card">
                    <div class="category-icon"><i class="bi bi-egg-fried"></i></div>
                    <h6>Plats</h6>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="category-card">
                    <div class="category-icon"><i class="bi bi-pizza"></i></div>
                    <h6>Pizzas</h6>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="category-card">
                    <div class="category-icon"><i class="bi bi-cake"></i></div>
                    <h6>Desserts</h6>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="category-card">
                    <div class="category-icon"><i class="bi bi-cup-straw"></i></div>
                    <h6>Boissons</h6>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="category-card">
                    <div class="category-icon"><i class="bi bi-wine"></i></div>
                    <h6>Cocktails</h6>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Comment ça marche -->
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center font-playfair mb-5">Comment ça marche</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h5>Scannez le QR</h5>
                    <p class="text-muted">Utilisez votre téléphone pour scanner le QR code sur votre table</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h5>Choisissez vos plats</h5>
                    <p class="text-muted">Parcourez notre menu et ajoutez vos plats préférés au panier</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h5>Validez & dégustez</h5>
                    <p class="text-muted">Confirmez votre commande et savourez votre repas</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Avis Clients -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center font-playfair mb-5">Ce que disent nos clients</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="review-card">
                    <div class="rating-stars mb-2">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p>"Une expérience culinaire exceptionnelle ! Les plats sont délicieux et le service est impeccable."</p>
                    <h6 class="mb-0">Marie K.</h6>
                    <small class="text-muted">⭐⭐⭐⭐⭐</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="review-card">
                    <div class="rating-stars mb-2">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p>"Le système de commande par QR est super pratique. Je recommande vivement le burger royal !"</p>
                    <h6 class="mb-0">Jean P.</h6>
                    <small class="text-muted">⭐⭐⭐⭐⭐</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="review-card">
                    <div class="rating-stars mb-2">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star"></i>
                    </div>
                    <p>"Ambiance chaleureuse, plats savoureux et service rapide. Une adresse à découvrir absolument."</p>
                    <h6 class="mb-0">Sophie L.</h6>
                    <small class="text-muted">⭐⭐⭐⭐</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer-custom">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="font-playfair">ISSALE Restaurant</h5>
                <p class="mt-3" style="font-size: 0.95rem;">
                    Une expérience culinaire unique au cœur de la ville.
                </p>
                <div class="social-links">
                    <a href="#" class="social-link me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-link me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>
            <div class="col-md-4">
                <h5>Horaires</h5>
                <ul class="list-unstyled mt-3" style="font-size: 0.95rem;">
                    <li>Lun - Jeu: 11:00 - 22:00</li>
                    <li>Ven - Sam: 11:00 - 23:00</li>
                    <li>Dimanche: Fermé</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Contact</h5>
                <ul class="list-unstyled mt-3" style="font-size: 0.95rem;">
                    <li><i class="bi bi-geo-alt me-2"></i>Libreville, Gabon</li>
                    <li><i class="bi bi-telephone me-2"></i>+241 01 23 45 67</li>
                    <li><i class="bi bi-envelope me-2"></i>contact@issale.com</li>
                </ul>
            </div>
        </div>
        <hr class="mt-4" style="border-color: rgba(255,255,255,0.1);">
        <p class="text-center mt-4" style="font-size: 0.85rem; opacity: 0.6;">
            &copy; 2026 ISSALE Restaurant. Tous droits réservés.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
