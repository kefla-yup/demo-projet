CREATE DATABASE takalo;
USE takalo;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    adresse TEXT,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Objects
CREATE TABLE IF NOT EXISTS objets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    prix_estime DECIMAL(10,2) DEFAULT NULL,
    categorie_id INT NOT NULL,
    user_id INT NOT NULL,
    photo VARCHAR(255),
    est_disponible TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (categorie_id),
    INDEX (user_id),
    CONSTRAINT fk_objets_categories FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE CASCADE,
    CONSTRAINT fk_objets_users FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Object photos (multiple photos per object)
CREATE TABLE IF NOT EXISTS object_photos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    objet_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (objet_id),
    CONSTRAINT fk_photos_objets FOREIGN KEY (objet_id) REFERENCES objets(id) ON DELETE CASCADE
);

-- Exchanges
CREATE TABLE IF NOT EXISTS echanges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    objet_propose_id INT NOT NULL,
    objet_demande_id INT NOT NULL,
    proposeur_id INT NOT NULL,
    proprietaire_id INT NOT NULL,
    statut ENUM('en_attente','accepte','refuse','annule') DEFAULT 'en_attente',
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (objet_propose_id),
    INDEX (objet_demande_id),
    INDEX (proposeur_id),
    INDEX (proprietaire_id),
    CONSTRAINT fk_echanges_op FOREIGN KEY (objet_propose_id) REFERENCES objets(id) ON DELETE CASCADE,
    CONSTRAINT fk_echanges_od FOREIGN KEY (objet_demande_id) REFERENCES objets(id) ON DELETE CASCADE,
    CONSTRAINT fk_echanges_proposeur FOREIGN KEY (proposeur_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_echanges_proprietaire FOREIGN KEY (proprietaire_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Ensure columns exist (useful when running against an older schema)
ALTER TABLE users ADD COLUMN IF NOT EXISTS is_admin TINYINT(1) DEFAULT 0;
ALTER TABLE objets ADD COLUMN IF NOT EXISTS prix_estime DECIMAL(10,2) DEFAULT NULL;

-- Populate default categories (INSERT IGNORE avoids duplicate key errors)
INSERT IGNORE INTO categories (nom) VALUES
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
('Livre Harry Potter', 'Livre Harry Potter et la pierre philosophale, bon état', 2, 1, 'hp.png'),
('T-shirt Adidas', 'T-shirt Adidas taille M, couleur bleu, jamais porté', 1, 1, 'ad.png'),
('Casque audio Sony', 'Casque audio Sony WH-1000XM3, excellent état', 4, 1, 'ca.png');