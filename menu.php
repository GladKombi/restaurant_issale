<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISSALE Restaurant - Menu</title>
    <!-- Bootstrap & Fonts (même imports) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Styles hérités de l'accueil + spécifiques menu */
        .product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
            height: 100%;
            border: none;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        .product-image {
            height: 200px;
            background: #f0ebe5;
            position: relative;
            overflow: hidden;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-body {
            padding: 20px;
        }
        
        .product-name {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 4px;
        }
        
        .product-description {
            font-size: 0.9rem;
            color: #6c757d;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .product-price {
            font-weight: 600;
            color: var(--primary);
            font-size: 1.2rem;
        }
        
        .badge-custom {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        .badge-new { background: var(--secondary); color: #1A120E; }
        .badge-spicy { background: #E74C3C; color: white; }
        .badge-popular { background: var(--primary); color: white; }
        
        .category-tabs .nav-link {
            color: #6c757d;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .category-tabs .nav-link.active {
            background: var(--primary);
            color: white;
        }
        
        .category-tabs .nav-link:hover:not(.active) {
            background: rgba(139,26,26,0.08);
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box input {
            padding-left: 45px;
            border-radius: 12px;
            border: 2px solid #e5ddd5;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }
        
        .search-box input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(139,26,26,0.1);
        }
        
        .search-box i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .btn-add-cart {
            background: var(--primary);
            color: white;
            border: none;
            padding: 6px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .btn-add-cart:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
        }
        
        .btn-add-cart:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Toast notification */
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .toast-custom {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            border: none;
            padding: 16px 24px;
        }
        
        .toast-custom .toast-body {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .toast-custom .toast-body i {
            font-size: 1.5rem;
            color: var(--success);
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
                    <a class="nav-link active" href="menu.html">Menu</a>
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
                    <a href="cart.html" class="btn btn-primary-custom ms-lg-3">
                        <i class="bi bi-bag-check me-2"></i>Panier
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Menu Section -->
<section class="py-5 mt-5">
    <div class="container">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <h1 class="font-playfair display-5">Notre Menu</h1>
                <p class="text-muted">Découvrez nos délicieuses créations préparées avec des ingrédients frais</p>
            </div>
            <div class="col-lg-4">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control" placeholder="Rechercher un plat..." id="searchInput">
                </div>
            </div>
        </div>
        
        <!-- Catégories Tabs -->
        <div class="category-tabs mb-4">
            <ul class="nav nav-pills" id="categoryTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-category="all">Tous</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-category="entrees">Entrées</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-category="plats">Plats</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-category="pizzas">Pizzas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-category="desserts">Desserts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-category="boissons">Boissons</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-category="cocktails">Cocktails</a>
                </li>
            </ul>
        </div>
        
        <!-- Produits Grid -->
        <div class="row g-4" id="productsGrid">
            <!-- Les produits seront chargés dynamiquement -->
        </div>
    </div>
</section>

<!-- Toast Container -->
<div class="toast-container">
    <div id="notificationToast" class="toast toast-custom" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            <i class="bi bi-check-circle-fill"></i>
            <span id="toastMessage">Produit ajouté au panier !</span>
        </div>
    </div>
</div>

<!-- Product Detail Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-md-6">
                        <img src="" alt="Produit" class="img-fluid rounded-start" id="modalImage" style="height: 100%; object-fit: cover; min-height: 300px;">
                    </div>
                    <div class="col-md-6 p-4">
                        <h3 class="font-playfair" id="modalName">Nom du produit</h3>
                        <p class="text-muted" id="modalDescription">Description du produit</p>
                        <div class="mb-3">
                            <span class="badge badge-new me-1" id="modalTags"></span>
                        </div>
                        <div class="mb-3">
                            <span class="badge bg-light text-dark me-1">🌾 Gluten</span>
                            <span class="badge bg-light text-dark">🥛 Lactose</span>
                        </div>
                        <h4 class="text-primary" id="modalPrice">5000 FC</h4>
                        <div class="d-flex align-items-center gap-3 mt-4">
                            <div class="input-group" style="width: 120px;">
                                <button class="btn btn-outline-secondary" type="button" id="decrementQty">-</button>
                                <input type="text" class="form-control text-center" value="1" id="modalQuantity">
                                <button class="btn btn-outline-secondary" type="button" id="incrementQty">+</button>
                            </div>
                            <button class="btn btn-primary-custom flex-grow-1" id="addToCartModal">
                                <i class="bi bi-bag-plus me-2"></i>Ajouter au panier
                            </button>
                        </div>
                        <button class="btn btn-link text-muted mt-3" data-bs-dismiss="modal">
                            <i class="bi bi-arrow-left me-2"></i>Continuer le menu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
    // Données produits de démonstration
    const products = [
        {
            id: 1,
            name: "Burger Royal",
            description: "Bœuf angus, cheddar affiné, caramel d'oignons, bacon croustillant",
            price: 5000,
            category: "plats",
            image: "https://via.placeholder.com/400x300/8B1A1A/FFFFFF?text=Burger+Royal",
            tags: ["populaire", "épicé"],
            allergens: ["gluten", "lait"]
        },
        {
            id: 2,
            name: "Salade César",
            description: "Salade verte, poulet grillé, parmesan, croûtons maison",
            price: 4500,
            category: "entrees",
            image: "https://via.placeholder.com/400x300/D4A373/FFFFFF?text=Salade+Cesar",
            tags: ["nouveau"],
            allergens: ["lait", "œuf"]
        },
        {
            id: 3,
            name: "Pizza Margherita",
            description: "Sauce tomate, mozzarella di bufala, basilic frais",
            price: 6000,
            category: "pizzas",
            image: "https://via.placeholder.com/400x300/C0392B/FFFFFF?text=Pizza+Margherita",
            tags: ["populaire"],
            allergens: ["gluten", "lait"]
        },
        {
            id: 4,
            name: "Boeuf Bourguignon",
            description: "Boeuf mijoté aux légumes, vin rouge et herbes de Provence",
            price: 8500,
            category: "plats",
            image: "https://via.placeholder.com/400x300/5C0E0E/FFFFFF?text=Boeuf+Bourguignon",
            tags: ["populaire"],
            allergens: []
        },
        {
            id: 5,
            name: "Tarte Tatin",
            description: "Tarte aux pommes caramélisées, crème fraîche",
            price: 3500,
            category: "desserts",
            image: "https://via.placeholder.com/400x300/D4A373/FFFFFF?text=Tarte+Tatin",
            tags: ["nouveau"],
            allergens: ["gluten", "lait"]
        },
        {
            id: 6,
            name: "Jus de Fruit Frais",
            description: "Jus pressé de fruits de saison",
            price: 2000,
            category: "boissons",
            image: "https://via.placeholder.com/400x300/27AE60/FFFFFF?text=Jus+de+Fruit",
            tags: [],
            allergens: []
        },
        {
            id: 7,
            name: "Brochettes de Poulet",
            description: "Brochettes marinées aux épices, servies avec frites maison",
            price: 5500,
            category: "plats",
            image: "https://via.placeholder.com/400x300/E67E22/FFFFFF?text=Brochettes",
            tags: ["épicé"],
            allergens: []
        },
        {
            id: 8,
            name: "Mojito Classique",
            description: "Rhum blanc, menthe fraîche, citron vert, eau gazeuse",
            price: 4000,
            category: "cocktails",
            image: "https://via.placeholder.com/400x300/2ECC71/FFFFFF?text=Mojito",
            tags: [],
            allergens: []
        }
    ];

    // State
    let cart = JSON.parse(localStorage.getItem('issaleCart')) || [];
    let currentProductId = null;

    // Fonctions
    function renderProducts(category = 'all', search = '') {
        const grid = document.getElementById('productsGrid');
        let filtered = products;

        if (category !== 'all') {
            filtered = filtered.filter(p => p.category === category);
        }

        if (search) {
            const term = search.toLowerCase();
            filtered = filtered.filter(p => 
                p.name.toLowerCase().includes(term) || 
                p.description.toLowerCase().includes(term)
            );
        }

        if (filtered.length === 0) {
            grid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search" style="font-size: 3rem; color: #dee2e6;"></i>
                    <h5 class="mt-3">Aucun produit trouvé</h5>
                    <p class="text-muted">Essayez une autre recherche ou catégorie</p>
                </div>
            `;
            return;
        }

        grid.innerHTML = filtered.map(product => `
            <div class="col-lg-3 col-md-4 col-6">
                <div class="product-card" data-product-id="${product.id}">
                    <div class="product-image">
                        <img src="${product.image}" alt="${product.name}" loading="lazy">
                        ${product.tags && product.tags.includes('populaire') ? 
                            '<span class="badge badge-popular position-absolute top-0 end-0 m-2">⭐ Populaire</span>' : ''}
                        ${product.tags && product.tags.includes('nouveau') ? 
                            '<span class="badge badge-new position-absolute top-0 end-0 m-2">✨ Nouveau</span>' : ''}
                    </div>
                    <div class="product-body">
                        <h6 class="product-name">${product.name}</h6>
                        <p class="product-description">${product.description}</p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="product-price">${product.price.toLocaleString()} FC</span>
                            <button class="btn-add-cart" onclick="addToCart(${product.id})">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        // Click sur les cartes pour ouvrir le modal
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('.btn-add-cart')) {
                    const id = parseInt(this.dataset.productId);
                    openProductModal(id);
                }
            });
        });
    }

    function addToCart(productId) {
        const product = products.find(p => p.id === productId);
        if (!product) return;

        const existing = cart.find(item => item.id === productId);
        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({ ...product, quantity: 1 });
        }

        localStorage.setItem('issaleCart', JSON.stringify(cart));
        updateCartCount();
        showToast(`${product.name} ajouté au panier !`, 'success');
    }

    function updateCartCount() {
        const count = cart.reduce((sum, item) => sum + item.quantity, 0);
        document.getElementById('cartCount').textContent = count;
        document.getElementById('cartCount').style.display = count > 0 ? 'block' : 'none';
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('notificationToast');
        const toastMessage = document.getElementById('toastMessage');
        const icon = toast.querySelector('.toast-body i');
        
        toastMessage.textContent = message;
        
        if (type === 'success') {
            icon.className = 'bi bi-check-circle-fill text-success';
        } else if (type === 'error') {
            icon.className = 'bi bi-x-circle-fill text-danger';
        } else {
            icon.className = 'bi bi-info-circle-fill text-info';
        }
        
        const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
        bsToast.show();
    }

    function openProductModal(productId) {
        const product = products.find(p => p.id === productId);
        if (!product) return;

        currentProductId = productId;
        
        document.getElementById('modalImage').src = product.image;
        document.getElementById('modalName').textContent = product.name;
        document.getElementById('modalDescription').textContent = product.description;
        document.getElementById('modalPrice').textContent = `${product.price.toLocaleString()} FC`;
        document.getElementById('modalQuantity').value = 1;
        
        // Tags
        const tagsContainer = document.getElementById('modalTags');
        tagsContainer.innerHTML = product.tags.map(tag => {
            const className = tag === 'populaire' ? 'badge-popular' : 
                             tag === 'nouveau' ? 'badge-new' : 
                             tag === 'épicé' ? 'badge-spicy' : '';
            return `<span class="badge ${className} me-1">${tag}</span>`;
        }).join('');

        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        renderProducts();
        updateCartCount();

        // Filtres catégories
        document.querySelectorAll('#categoryTabs .nav-link').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('#categoryTabs .nav-link').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                const category = this.dataset.category;
                const search = document.getElementById('searchInput').value;
                renderProducts(category, search);
            });
        });

        // Recherche
        document.getElementById('searchInput').addEventListener('input', function() {
            const search = this.value;
            const activeTab = document.querySelector('#categoryTabs .nav-link.active');
            const category = activeTab ? activeTab.dataset.category : 'all';
            renderProducts(category, search);
        });

        // Modal controls
        document.getElementById('decrementQty').addEventListener('click', function() {
            const input = document.getElementById('modalQuantity');
            let val = parseInt(input.value);
            if (val > 1) {
                input.value = val - 1;
            }
        });

        document.getElementById('incrementQty').addEventListener('click', function() {
            const input = document.getElementById('modalQuantity');
            let val = parseInt(input.value);
            input.value = val + 1;
        });

        document.getElementById('addToCartModal').addEventListener('click', function() {
            const quantity = parseInt(document.getElementById('modalQuantity').value);
            const product = products.find(p => p.id === currentProductId);
            if (product) {
                for (let i = 0; i < quantity; i++) {
                    addToCart(currentProductId);
                }
                const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
                modal.hide();
            }
        });
    });
</script>
</body>
</html>
