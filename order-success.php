<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISSALE Restaurant - Commande Confirmée</title>
    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .success-icon {
            font-size: 5rem;
            color: #2D7D46;
            animation: successPulse 1.5s ease;
        }
        
        @keyframes successPulse {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .order-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        
        .order-detail {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0ebe5;
        }
        
        .order-detail:last-child {
            border-bottom: none;
        }
        
        .confetti {
            position: fixed;
            pointer-events: none;
            z-index: 999;
        }
    </style>
</head>
<body>

<!-- Navbar simplifiée -->
<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.html">
            ISSALE
            <span class="brand-sub">Restaurant</span>
        </a>
    </div>
</nav>

<!-- Success Section -->
<section class="py-5 mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center mb-4">
                    <div class="success-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h1 class="font-playfair display-6 mt-3">Commande confirmée !</h1>
                    <p class="text-muted">Votre commande a été envoyée à la cuisine</p>
                </div>
                
                <div class="order-card">
                    <div class="text-center mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary p-3 fs-6">
                            <i class="bi bi-upc-scan me-2"></i>
                            #<span id="orderNumber">IS-5123</span>
                        </span>
                    </div>
                    
                    <div id="orderDetails">
                        <!-- Rempli par JS -->
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total TTC</span>
                        <span class="h5 mb-0" id="orderTotal">14 500 FC</span>
                    </div>
                    
                    <div class="mt-3">
                        <span class="badge bg-warning text-dark p-2">
                            <i class="bi bi-clock me-1"></i>En préparation
                        </span>
                        <span class="badge bg-light text-dark p-2 ms-2">
                            <i class="bi bi-truck me-1"></i><span id="orderType">Sur place</span>
                        </span>
                    </div>
                </div>
                
                <div class="d-grid gap-3 mt-4">
                    <a href="order-tracking.html?id=IS-5123" class="btn btn-primary-custom">
                        <i class="bi bi-graph-up-arrow me-2"></i>Suivre ma commande
                    </a>
                    <a href="menu.html" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Voir le menu
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        const orderId = params.get('id') || 'IS-' + Math.floor(Math.random() * 9000 + 1000);
        
        document.getElementById('orderNumber').textContent = orderId;
        
        // Récupérer la commande du localStorage
        const order = JSON.parse(localStorage.getItem('issaleOrder'));
        
        if (order) {
            // Afficher les détails
            const detailsContainer = document.getElementById('orderDetails');
            detailsContainer.innerHTML = order.items.map(item => `
                <div class="order-detail">
                    <span>${item.name} × ${item.quantity}</span>
                    <span>${(item.price * item.quantity).toLocaleString()} FC</span>
                </div>
            `).join('');
            
            document.getElementById('orderTotal').textContent = order.total.toLocaleString() + ' FC';
            document.getElementById('orderType').textContent = order.type === 'surplace' ? 'Sur place' : 'À emporter';
            
            // Nettoyer le panier
            localStorage.removeItem('issaleCart');
        }
    });
</script>

</body>
</html>
