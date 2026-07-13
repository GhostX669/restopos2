CREATE DATABASE IF NOT EXISTS restopos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restopos;

CREATE TABLE role (
    id_role INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE,
    couleur_fond VARCHAR(20) NOT NULL,
    couleur_texte VARCHAR(20) NOT NULL,
    couleur VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE utilisateur (
    id_utilisateur INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    login VARCHAR(50) NOT NULL UNIQUE,
    mot_passe VARCHAR(255) NOT NULL,
    email VARCHAR(150) NULL,
    initiales VARCHAR(10) NULL,
    actif TINYINT(1) NOT NULL DEFAULT 1,
    derniere_connexion DATETIME NULL,
    id_role INT UNSIGNED NOT NULL,
    CONSTRAINT fk_utilisateur_role FOREIGN KEY (id_role) REFERENCES role(id_role)
) ENGINE=InnoDB;

CREATE TABLE statut_table (
    id_statut_table INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) NOT NULL UNIQUE,
    libelle VARCHAR(50) NOT NULL,
    couleur_fond VARCHAR(20) NOT NULL,
    couleur_point VARCHAR(20) NOT NULL,
    couleur_texte VARCHAR(20) NOT NULL,
    couleur_bordure VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE statut_commande (
    id_statut_commande INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) NOT NULL UNIQUE,
    libelle VARCHAR(50) NOT NULL,
    couleur_fond VARCHAR(20) NOT NULL,
    couleur_texte VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE mode_paiement (
    id_mode_paiement INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE,
    couleur VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE categorie (
    id_categorie INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE table_restaurant (
    id_table INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(20) NOT NULL UNIQUE,
    capacite TINYINT UNSIGNED NOT NULL,
    depuis TIME NULL,
    note VARCHAR(150) NULL,
    id_statut_table INT UNSIGNED NOT NULL,
    id_utilisateur INT UNSIGNED NULL,
    CONSTRAINT fk_table_statut FOREIGN KEY (id_statut_table) REFERENCES statut_table(id_statut_table),
    CONSTRAINT fk_table_utilisateur FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
) ENGINE=InnoDB;

CREATE TABLE produit (
    id_produit INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    disponible TINYINT(1) NOT NULL DEFAULT 1,
    image_url VARCHAR(255) NULL,
    id_categorie INT UNSIGNED NOT NULL,
    CONSTRAINT fk_produit_categorie FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie)
) ENGINE=InnoDB;

CREATE TABLE commande (
    id_commande INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(20) NOT NULL UNIQUE,
    heure_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    montant_total DECIMAL(10,2) NOT NULL DEFAULT 0,
    id_table INT UNSIGNED NOT NULL,
    id_utilisateur INT UNSIGNED NOT NULL,
    id_statut_commande INT UNSIGNED NOT NULL,
    CONSTRAINT fk_commande_table FOREIGN KEY (id_table) REFERENCES table_restaurant(id_table),
    CONSTRAINT fk_commande_utilisateur FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur),
    CONSTRAINT fk_commande_statut FOREIGN KEY (id_statut_commande) REFERENCES statut_commande(id_statut_commande)
) ENGINE=InnoDB;

CREATE TABLE ligne_commande (
    id_ligne_commande INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quantite SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    id_commande INT UNSIGNED NOT NULL,
    id_produit INT UNSIGNED NOT NULL,
    CONSTRAINT fk_ligne_commande FOREIGN KEY (id_commande) REFERENCES commande(id_commande) ON DELETE CASCADE,
    CONSTRAINT fk_ligne_produit FOREIGN KEY (id_produit) REFERENCES produit(id_produit)
) ENGINE=InnoDB;

CREATE TABLE transaction_paiement (
    id_transaction INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    montant DECIMAL(10,2) NOT NULL,
    date_heure DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_commande INT UNSIGNED NOT NULL,
    id_mode_paiement INT UNSIGNED NOT NULL,
    CONSTRAINT fk_transaction_commande FOREIGN KEY (id_commande) REFERENCES commande(id_commande),
    CONSTRAINT fk_transaction_mode FOREIGN KEY (id_mode_paiement) REFERENCES mode_paiement(id_mode_paiement)
) ENGINE=InnoDB;

INSERT INTO role (libelle, couleur_fond, couleur_texte, couleur) VALUES
('Administrateur', '#ede9fe', '#7c3aed', '#8b5cf6'),
('Gérant', '#dbeafe', '#1d4ed8', '#3b82f6'),
('Caissier', '#fef3c7', '#b45309', '#f59e0b'),
('Serveur', '#d1fae5', '#047857', '#10b981');

INSERT INTO statut_table (code, libelle, couleur_fond, couleur_point, couleur_texte, couleur_bordure) VALUES
('available', 'Libre', '#ecfdf5', '#10b981', '#047857', '#a7f3d0'),
('occupied', 'Occupée', '#fff7ed', '#f97316', '#c2410c', '#fed7aa'),
('reserved', 'Réservée', '#eff6ff', '#3b82f6', '#1d4ed8', '#bfdbfe'),
('dirty', 'À nettoyer', '#f9fafb', '#9ca3af', '#6b7280', '#e5e7eb');

INSERT INTO statut_commande (code, libelle, couleur_fond, couleur_texte) VALUES
('pending', 'En attente', '#fef3c7', '#b45309'),
('preparing', 'En préparation', '#dbeafe', '#1d4ed8'),
('ready', 'Prêt à servir', '#d1fae5', '#047857'),
('served', 'Servi', '#f3f4f6', '#6b7280'),
('cancelled', 'Annulé', '#fee2e2', '#dc2626');

INSERT INTO mode_paiement (libelle, couleur) VALUES
('Espèces', '#f97316'),
('Carte bancaire', '#3b82f6'),
('Mobile Money', '#10b981'),
('Chèque', '#8b5cf6');

INSERT INTO categorie (libelle) VALUES
('Plats principaux'),
('Entrées'),
('Pizzas'),
('Boissons'),
('Cafés & Thés');

INSERT INTO utilisateur (nom, login, mot_passe, email, initiales, actif, id_role) VALUES
('Moussa Traoré', 'admin', SHA2('admin123', 256), 'm.traore@restaurant.ci', 'MT', 1, 1),
('Fatoumata Koné', 'gerant', SHA2('gerant123', 256), 'f.kone@restaurant.ci', 'FK', 1, 2),
('Ibrahima Kouyaté', 'caissier', SHA2('caissier123', 256), 'i.kouyate@restaurant.ci', 'IK', 1, 3),
('Aminata Diallo', 'serveur', SHA2('serveur123', 256), 'a.diallo@restaurant.ci', 'AD', 1, 4);

INSERT INTO table_restaurant (nom, capacite, depuis, note, id_statut_table, id_utilisateur) VALUES
('T01', 2, NULL, NULL, 1, NULL),
('T02', 4, '12:14', NULL, 2, 4),
('T03', 4, '12:31', NULL, 2, 4),
('T04', 6, '14:00', 'Réservation Diallo', 3, NULL),
('T05', 2, NULL, NULL, 1, NULL),
('T06', 4, NULL, NULL, 4, NULL),
('T07', 8, '13:05', NULL, 2, 4),
('T08', 2, NULL, NULL, 1, NULL),
('T09', 4, '13:42', NULL, 2, 4),
('T10', 6, '19:00', 'Réservation Koné', 3, NULL),
('T11', 4, NULL, NULL, 1, NULL),
('T12', 2, NULL, NULL, 4, NULL),
('T13', 4, NULL, NULL, 1, NULL),
('T14', 6, '14:10', NULL, 2, 4),
('T15', 4, NULL, NULL, 1, NULL);

INSERT INTO produit (nom, prix, disponible, image_url, id_categorie) VALUES
('Poulet braisé', 7500, 1, 'assets/images/poulet-braise.jpg', 1),
('Thiéboudienne', 6500, 1, 'assets/images/thieboudienne.avif', 1),
('Mafé bœuf', 8000, 1, 'assets/images/mafe-boeuf.avif', 1),
('Attiéké poisson', 5500, 1, 'assets/images/attieke-poisson.jpg', 1),
('Salade niçoise', 3500, 1, 'assets/images/salade-nicoise.jpg', 2),
('Pizza margherita', 5000, 1, 'assets/images/pizza-margherita.jpg', 3),
('Jus de gingembre', 1500, 1, 'assets/images/jus-gingembre.jpg', 4),
('Café espresso', 1200, 1, 'assets/images/cafe-espresso.jpg', 5);
