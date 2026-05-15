DROP DATABASE roombook;
CREATE DATABASE IF NOT EXISTS roombook;
USE roombook;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'client') NOT NULL DEFAULT 'client',
    type_client ENUM('eleve', 'prof', 'autre') DEFAULT 'autre'
);

CREATE TABLE salles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    capacite INT NOT NULL,
    localisation VARCHAR(100)
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NULL,
    salle_id INT NOT NULL,
    date_reservation DATE NOT NULL,
    heure_debut TIME NOT NULL,
    heure_fin TIME NOT NULL,
    responsable VARCHAR(100) NOT NULL,
    motif TEXT,
    statut ENUM('en_attente', 'confirmee', 'refusee', 'annulee') DEFAULT 'en_attente',

    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
    ON DELETE SET NULL,

    FOREIGN KEY (salle_id) REFERENCES salles(id)
    ON DELETE CASCADE
);
INSERT INTO salles (nom, capacite, localisation) VALUES
('Salle A', 30, 'Bloc A'),
('Salle B', 40, 'Bloc B'),
('Salle Informatique', 25, 'Bloc Info'),
('Amphi 1', 100, 'Bloc Principal'),
('Salle C', 35, 'Bloc C');