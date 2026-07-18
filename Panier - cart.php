<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISSALE Restaurant - Mon Panier</title>
    <!-- Bootstrap & Fonts (mêmes imports) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Styles hérités + spécifiques panier */
        .cart-item {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
        }
        
        .cart-item:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        
        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .quantity-control button {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid #dee2e6;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .quantity-control button:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .quantity-control .qty {
            font-weight: 600;
            min-width: 30px;
            text-align: center;
        }
        
        .cart-summary {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            position: sticky;
            top: 100px;
        }
        
        .cart-summary .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0ebe5;
        }
        
        .cart-summary .total-row:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 1.2rem;
            padding-top: 16px;
        }
        
        .empty-cart {
            text-align: center;
            padding: 80px 20px;
        }
        
        .empty-cart i {
            font-size: 4rem;
            color: #dee2e6;
        }
        
        .order-type-selector {
            display: flex;
            gap: 12px;
            margin: 16px 0;
        }
        
        .order-type-selector .btn-check:checked+.btn {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .order-type-selector .btn {
            padding: 10px 24px;
            border-radius: 12px;
            border: 2px solid #dee2e6;
            transition: all 0.3s ease;
        }
        
        .order-type-selector .btn:hover {
            border-color: var(--primary);
        }
    </style>
</head>
<body>

<!-- Navbar (identique) -->
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
                    <a class="nav-link" href="index.html">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="menu.html">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active position-relative" href="cart.html">
                        <i class="bi bi-cart3 fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount">
                            0
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Cart Section -->
<section class="py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <h1 class="font-playfair display-6 mb-4">Mon Panier</h1>
                
                <div id="cartItemsContainer">
                    <!-- Les items seront chargés dynamiquement -->
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="cart-summary" id="cartSummary">
                    <h5 class="font-playfair mb-3">Résumé</h5>
                    <div id="summaryContent">
                        <!-- Rempli par JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer (identique) -->
<footer class="footer-custom mt-5">
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
<script>
    // Récupérer le panier
    let cart = JSON.parse(localStorage.getItem('issaleCart')) || [];

    function renderCart() {
        const container = document.getElementById('cartItemsContainer');
        const summary = document.getElementById('summaryContent');
        
        if (cart.length === 0) {
            container.innerHTML = `
                <div class="empty-cart">
                    <i class="bi bi-cart3"></i>
                    <h4 class="mt-3">Votre panier est vide</h4>
                    <p class="text-muted">Parcourez notre menu et ajoutez vos plats préférés</p>
                    <a href="menu.html" class="btn btn-primary-custom mt-3">
                        <i class="bi bi-arrow-left me-2"></i>Voir le menu
                    </a>
                </div>
            `;
            summary.innerHTML = `
                <p class="text-muted text-center py-4">Ajoutez des articles à votre panier</p>
            `;
            document.getElementById('cartCount').textContent = '0';
            return;
        }

        // Afficher les items
        container.innerHTML = cart.map(item => `
            <div class="cart-item">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <img src="${item.image}" alt="${item.name}" class="img-fluid">
                    </div>
                    <div class="col">
                        <h6 class="mb-1">${item.name}</h6>
                        <p class="text-muted small mb-2">${item.price.toLocaleString()} FC</p>
                        <div class="quantity-control">
                            <button onclick="updateQuantity(${item.id}, -1)" aria-label="Diminuer quantité">
                                <i class="bi bi-dash"></i>
                            </button>
                            <span class="qty">${item.quantity}</span>
                            <button onclick="updateQuantity(${item.id}, 1)" aria-label="Augmenter quantité">
                                <i class="bi bi-plus"></i>
                            </button>
                            <button onclick="removeItem(${item.id})" class="text-danger bg-transparent border-0 ms-2">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-auto text-end">
                        <strong>${(item.price * item.quantity).toLocaleString()} FC</strong>
                    </div>
                </div>
            </div>
        `).join('');

        // Calculer le total
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const total = subtotal;

        // Résumé
        summary.innerHTML = `
            <div class="total-row">
                <span>Sous-total</span>
                <span>${subtotal.toLocaleString()} FC</span>
            </div>
            <div class="total-row">
                <span>Frais de service</span>
                <span>0 FC</span>
            </div>
            <div class="total-row">
                <span>Total TTC</span>
                <span><strong>${total.toLocaleString()} FC</strong></span>
            </div>
            
            <div class="mt-3">
                <label class="form-label fw-bold">Type de commande</label>
                <div class="order-type-selector">
                    <input type="radio" class="btn-check" name="orderType" id="surplace" value="surplace" checked>
                    <label class="btn btn-outline-secondary" for="surplace">
                        <i class="bi bi-building me-1"></i>Sur place
                    </label>
                    
                    <input type="radio" class="btn-check" name="orderType" id="emporter" value="emporter">
                    <label class="btn btn-outline-secondary" for="emporter">
                        <i class="bi bi-box-seam me-1"></i>À emporter
                    </label>
                </div>
            </div>
            
            <button class="btn btn-primary-custom w-100 mt-3" onclick="validateOrder()">
                <i class="bi bi-check2-circle me-2"></i>Valider la commande
            </button>
            <a href="menu.html" class="btn btn-link w-100 mt-2">
                <i class="bi bi-arrow-left me-2"></i>Continuer le menu
            </a>
        `;

        // Mettre à jour le badge
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        document.getElementById('cartCount').textContent = totalItems;
    }

    function updateQuantity(productId, delta) {
        const item = cart.find(i => i.id === productId);
        if (!item) return;
        
        item.quantity += delta;
        if (item.quantity <= 0) {
            cart = cart.filter(i => i.id !== productId);
        }
        
        localStorage.setItem('issaleCart', JSON.stringify(cart));
        renderCart();
    }

    function removeItem(productId) {
        cart = cart.filter(i => i.id !== productId);
        localStorage.setItem('issaleCart', JSON.stringify(cart));
        renderCart();
        
        // Toast notification
        showToast('Article retiré du panier');
    }

    function validateOrder() {
        if (cart.length === 0) {
            alert('Votre panier est vide !');
            return;
        }
        
        const orderType = document.querySelector('input[name="orderType"]:checked');
        const type = orderType ? orderType.value : 'surplace';
        
        // Simulation de validation
        const orderNumber = 'IS-' + Math.floor(Math.random() * 9000 + 1000);
        
        // Sauvegarder la commande
        const order = {
            id: orderNumber,
            items: cart,
            total: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
            type: type,
            date: new Date().toISOString(),
            status: 'confirmee'
        };
        
        localStorage.setItem('issaleOrder', JSON.stringify(order));
        
        // Rediriger vers la confirmation
        window.location.href = 'order-success.html?id=' + orderNumber;
    }

    function showToast(message) {
        // Créer un toast simple
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 end-0 p-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-body d-flex align-items-center">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    ${message}
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // Charger le panier au chargement
    document.addEventListener('DOMContentLoaded', renderCart);
</script>
</body>
</html>
