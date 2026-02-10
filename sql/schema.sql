-- Création de la base de données
CREATE DATABASE takalo;
USE takalo;

-- Table des utilisateurs
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    adresse TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des catégories d'objets
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des objets
CREATE TABLE objets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    categorie_id INT NOT NULL,
    user_id INT NOT NULL,
    photo VARCHAR(255),
    est_disponible BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des échanges
CREATE TABLE echanges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    objet_propose_id INT NOT NULL,
    objet_demande_id INT NOT NULL,
    proposeur_id INT NOT NULL,
    proprietaire_id INT NOT NULL,
    statut ENUM('en_attente', 'accepte', 'refuse', 'annule') DEFAULT 'en_attente',
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (objet_propose_id) REFERENCES objets(id) ON DELETE CASCADE,
    FOREIGN KEY (objet_demande_id) REFERENCES objets(id) ON DELETE CASCADE,
    FOREIGN KEY (proposeur_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (proprietaire_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion des catégories
INSERT INTO categories (nom) VALUES 
('Vêtements'),
('Livres'),
('DVD/Blu-ray'),
('Électronique'),
('Maison'),
('Sports'),
('Jouets'),
('Autre');

-- Insertion d'un utilisateur de test (mot de passe: test123)
INSERT INTO users (nom, email, password) VALUES 
('Test Utilisateur', 'test@example.com', '$2y$10$K4iVs6fwQeL2/4IkhF.jbO2w6Ej5Yy0y3zA6n5GJ5tW5d5J5L5V5W');

-- Insertion d'objets de test
INSERT INTO objets (nom, description, categorie_id, user_id, photo) VALUES
('Livre Harry Potter', 'Livre Harry Potter et la pierre philosophale, bon état', 2, 1, NULL),
('T-shirt Adidas', 'T-shirt Adidas taille M, couleur bleu, jamais porté', 1, 1, NULL),
('Casque audio Sony', 'Casque audio Sony WH-1000XM3, excellent état', 4, 1, NULL);