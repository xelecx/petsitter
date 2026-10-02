-- ============================================
-- PetSitter — Script de création de la BDD (version finale)
-- Jeu de données réaliste, avec de vrais hash fonctionnels
-- Mot de passe pour TOUS les comptes ci-dessous : password123
-- ============================================

CREATE DATABASE IF NOT EXISTS petsitter CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE petsitter;

DROP TABLE IF EXISTS avis;
DROP TABLE IF EXISTS candidatures;
DROP TABLE IF EXISTS annonces;
DROP TABLE IF EXISTS utilisateurs;

-- ============================================
-- Table : utilisateurs
-- role sert UNIQUEMENT a distinguer admin/user.
-- Proprietaire/gardien = casquette contextuelle (voir id_proprietaire / id_gardien plus bas)
-- ============================================
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- Table : annonces
-- ============================================
CREATE TABLE annonces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150) NOT NULL,
    type_animal VARCHAR(50) NOT NULL,
    ville VARCHAR(100) NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    description TEXT,
    statut ENUM('ouverte', 'pourvue', 'terminee') NOT NULL DEFAULT 'ouverte',
    id_proprietaire INT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_proprietaire) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    CONSTRAINT chk_dates CHECK (date_fin >= date_debut)
) ENGINE=InnoDB;

-- ============================================
-- Table : candidatures
-- Un gardien ne peut postuler qu'une seule fois a la meme annonce
-- ============================================
CREATE TABLE candidatures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message TEXT,
    statut ENUM('en_attente', 'acceptee', 'refusee') NOT NULL DEFAULT 'en_attente',
    date_candidature TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_annonce INT NOT NULL,
    id_gardien INT NOT NULL,

    FOREIGN KEY (id_annonce) REFERENCES annonces(id) ON DELETE CASCADE,
    FOREIGN KEY (id_gardien) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_candidature (id_annonce, id_gardien)
) ENGINE=InnoDB;

-- ============================================
-- Table : avis
-- Un avis n'est possible que sur une annonce "terminee",
-- et chaque utilisateur ne peut noter qu'une fois par garde
-- ============================================
CREATE TABLE avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    note TINYINT NOT NULL,
    commentaire TEXT,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_annonce INT NOT NULL,
    id_auteur INT NOT NULL,
    id_cible INT NOT NULL,

    FOREIGN KEY (id_annonce) REFERENCES annonces(id) ON DELETE CASCADE,
    FOREIGN KEY (id_auteur) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (id_cible) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    CONSTRAINT chk_note CHECK (note BETWEEN 1 AND 5),
    UNIQUE KEY unique_avis (id_annonce, id_auteur)
) ENGINE=InnoDB;


-- ============================================
-- Jeu de donnees de test
-- Mot de passe pour TOUS les comptes : password123
-- (hash bcrypt reel genere, compatible password_verify() de PHP)
-- ============================================

INSERT INTO utilisateurs (nom, email, password, role) VALUES
('Alice Martin', 'alice@example.com', '$2b$10$1.WzXidDH0ebHTXNhY/EUen2r5LsOra86O/JmJwf5vcl2a3oSvobO', 'user'),
('Bruno Petit', 'bruno@example.com', '$2b$10$1.WzXidDH0ebHTXNhY/EUen2r5LsOra86O/JmJwf5vcl2a3oSvobO', 'user'),
('Chloe Dubois', 'chloe@example.com', '$2b$10$1.WzXidDH0ebHTXNhY/EUen2r5LsOra86O/JmJwf5vcl2a3oSvobO', 'user'),
('David Roux', 'david@example.com', '$2b$10$1.WzXidDH0ebHTXNhY/EUen2r5LsOra86O/JmJwf5vcl2a3oSvobO', 'user'),
('Admin PetSitter', 'admin@petsitter.local', '$2b$10$1.WzXidDH0ebHTXNhY/EUen2r5LsOra86O/JmJwf5vcl2a3oSvobO', 'admin');

-- 4 annonces dans des etats varies (le sujet demande au moins 3)
INSERT INTO annonces (titre, type_animal, ville, date_debut, date_fin, description, statut, id_proprietaire) VALUES
('Garde de Milo pour le week-end', 'Chien', 'Lyon', '2026-10-20', '2026-10-22', 'Milo est un labrador tres calin, 2 balades par jour necessaires.', 'ouverte', 1),
('Garde de mon chat pendant les vacances', 'Chat', 'Paris', '2026-10-05', '2026-10-15', 'Chat independant, juste verifier gamelle et litiere.', 'pourvue', 2),
('Deplacement professionnel - garde perruche', 'Oiseau', 'Marseille', '2026-09-01', '2026-09-05', 'Cage deja installee, juste nourrir matin et soir.', 'terminee', 1),
('Garde de Rex pendant les vacances d ete', 'Chien', 'Lyon', '2026-08-10', '2026-08-20', 'Rex adore jouer, besoin d un grand jardin ou de balades longues.', 'terminee', 2);

-- 4 candidatures dans des etats varies
INSERT INTO candidatures (message, statut, id_annonce, id_gardien) VALUES
('Bonjour, je suis disponible et j adore les labradors !', 'en_attente', 1, 3),
('Je peux le garder, j ai deja garde des chiens de cette race.', 'en_attente', 1, 4),
('Disponible toute la semaine, j ai de l experience avec les chats.', 'acceptee', 2, 3),
('Je peux passer tous les jours pour nourrir la perruche.', 'acceptee', 3, 4);

-- 3 avis, chacun sur une paire (annonce, auteur) differente
INSERT INTO avis (note, commentaire, id_annonce, id_auteur, id_cible) VALUES
(5, 'Gardien tres serieux, la perruche etait en pleine forme au retour.', 3, 1, 4),
(4, 'Proprietaire clair dans ses consignes, tout s est bien passe.', 3, 4, 1),
(5, 'Rex etait fatigue mais ravi, super gardien.', 4, 2, 3);