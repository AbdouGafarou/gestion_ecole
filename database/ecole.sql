CREATE DATABASE IF NOT EXISTS gestion_ecole;
USE gestion_ecole;

-- Table des utilisateurs (admin, enseignants, élèves)
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'enseignant', 'eleve') NOT NULL
);

-- Table des classes
CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL
);

-- Table des matières
CREATE TABLE matieres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    classe_id INT,
    FOREIGN KEY (classe_id) REFERENCES classes(id)
);

-- Table des notes
CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    eleve_id INT,
    matiere_id INT,
    note FLOAT,
    date_note DATE,
    FOREIGN KEY (eleve_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (matiere_id) REFERENCES matieres(id)
);

-- Table des emplois du temps
CREATE TABLE emploi_du_temps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classe_id INT,
    matiere_id INT,
    jour VARCHAR(20),
    heure TIME,
    FOREIGN KEY (classe_id) REFERENCES classes(id),
    FOREIGN KEY (matiere_id) REFERENCES matieres(id)
);