-- ========================================================
-- BASE DE DONNÉES : restaurant_issale
-- ========================================================

CREATE DATABASE IF NOT EXISTS restaurant_issale 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE restaurant_issale;

-- ========================================================
-- 1. TABLE : users (Gestion des utilisateurs)
-- ========================================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    postnom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin', 'gestionnaire', 'serveur', 'cuisinier', 'client') DEFAULT 'client',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    supprimer INT DEFAULT 0
) ENGINE=InnoDB;

-- ========================================================
-- 2. TABLE : categories (Catégories de menu)
-- ========================================================
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    supprimer INT DEFAULT 0
) ENGINE=InnoDB;

-- ========================================================
-- 3. TABLE : menus (Articles du menu)
-- ========================================================
CREATE TABLE menus (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    preparation_time INT DEFAULT 15,
    stock_quantity INT NOT NULL DEFAULT 0,
    display_order INT DEFAULT 0,
    created_by INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    supprimer INT DEFAULT 0
) ENGINE=InnoDB;

-- ========================================================
-- 4. TABLE : tables (Gestion des tables)
-- ========================================================
CREATE TABLE tables (
    id INT PRIMARY KEY AUTO_INCREMENT,
    number VARCHAR(10) UNIQUE NOT NULL,
    capacity INT DEFAULT 4,
    qr_code VARCHAR(255) UNIQUE,
    qr_code_token VARCHAR(64) UNIQUE,
    status ENUM('available', 'occupied', 'reserved', 'cleaning') DEFAULT 'available',
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    supprimer INT DEFAULT 0
) ENGINE=InnoDB;

-- ========================================================
-- 5. TABLE : orders (Commandes principales)
-- ========================================================
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(20) UNIQUE NOT NULL,
    table_id INT NOT NULL,
    user_id INT,
    server_id INT,
    total_amount DECIMAL(10, 2) DEFAULT 0,
    status ENUM('en attente', 'confirme', 'en preparation', 'servis', 'payer', 'annuler') DEFAULT 'en attente',
    payment_status ENUM('en attente', 'payer', 'partially_paid') DEFAULT 'en attente',
    order_type ENUM('surplace', 'a emporter', 'livraison') DEFAULT 'surplace',
    special_notes TEXT,
    order_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    confirmed_at DATETIME,
    ready_at DATETIME,
    served_at DATETIME,
    stock_processed BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    supprimer INT DEFAULT 0
) ENGINE=InnoDB;

-- ========================================================
-- 6. TABLE : order_items (Lignes de commande)
-- ========================================================
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    menu_id INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    unit_price DECIMAL(10, 2) NOT NULL,
    notes TEXT,
    supprimer INT DEFAULT 0
) ENGINE=InnoDB;

-- ========================================================
-- 7. TABLE : payments (Paiements)
-- ========================================================
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method ENUM('especes', 'mobile_money', 'carte', 'virement') DEFAULT 'especes',
    reference VARCHAR(100),
    status ENUM('valide', 'annule') DEFAULT 'valide',
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    supprimer INT DEFAULT 0
) ENGINE=InnoDB;

-- ========================================================
-- 8. TABLE : stock_movements (Mouvements de stock)
-- ========================================================
CREATE TABLE stock_movements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    menu_id INT NOT NULL,
    type ENUM('entree', 'sortie', 'ajustement', 'perdus') NOT NULL,
    quantity INT NOT NULL,
    raison VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    supprimer INT DEFAULT 0
) ENGINE=InnoDB;

-- ========================================================
-- 9. TABLE : reviews (Avis clients)
-- ========================================================
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT UNIQUE NOT NULL,
    user_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    is_visible BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    supprimer INT DEFAULT 0
) ENGINE=InnoDB;

-- ========================================================
-- DONNÉES INITIALES (Optionnelles)
-- ========================================================

-- Insertion des catégories par défaut
INSERT INTO categories (nom) VALUES
('Entrées'),
('Plats Principaux'),
('Pizzas'),
('Grillades'),
('Desserts'),
('Boissons'),
('Cocktails');

-- Insertion du compte admin par défaut (password: Admin123!)
INSERT INTO users (nom, postnom, email, password, role) VALUES
('Admin', 'System', 'admin@restaurant-issale.com', '$2y$10$wRVQTThOdXXHEVIwYgUgvuh8bbSOWIhZVJR9jDm3T06H29tHpxa.e', 'admin');

-- Insertion de quelques tables par défaut
INSERT INTO tables (number, capacity) VALUES
('T-01', 4),
('T-02', 4),
('T-03', 6),
('T-04', 2),
('T-05', 8);

-- Insertion de quelques menus exemple
INSERT INTO menus (category_id, nom, description, price, created_by) VALUES
(1, 'Salade César', 'Salade verte, poulet grillé, parmesan, croûtons', 4500, 1),
(2, 'Boeuf Bourguignon', 'Boeuf mijoté aux légumes et vin rouge', 8500, 1),
(3, 'Pizza Margherita', 'Sauce tomate, mozzarella, basilic', 6000, 1),
(4, 'Brochettes de Poulet', 'Brochettes marinées servies avec frites', 5500, 1),
(5, 'Tarte Tatin', 'Tarte aux pommes caramélisées', 3500, 1),
(6, 'Jus de Fruit', 'Jus frais de saison', 2000, 1);

-- ========================================================
-- FIN DU SCRIPT
-- ========================================================
