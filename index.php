<?php
session_start();
include 'includes/db.php'; // Inclure la connexion à la base de données
include 'includes/functions.php'; // Inclure les fonctions utiles
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion d'école</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- En-tête -->
    <header class="header">
        <div class="container">
            <div class="logo">
            
    </header>

    <!-- Bannière principale -->
    <section class="banner">
        <div class="container">
            <h1>Bienvenue sur la plateforme de gestion d'école</h1>
            <p>Gérez facilement les emplois du temps, les notes et les utilisateurs.</p>
            <?php if (!isLoggedIn()) : ?>
                <a href="login.php" class="btn">Se connecter</a>
            <?php else : ?>
                <a href="dashboard.php" class="btn">Tableau de bord</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Section des fonctionnalités -->
    <section class="features">
        <div class="container">
            <h2>Fonctionnalités</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Emploi du temps</h3>
                    <p>Planifiez et consultez les emplois du temps des classes.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-book"></i>
                    <h3>Gestion des notes</h3>
                    <p>Enregistrez et consultez les notes des élèves.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-users"></i>
                    <h3>Gestion des utilisateurs</h3>
                    <p>Administrez les comptes des enseignants et des élèves.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section à propos -->
    <section class="about">
        <div class="container">
            <h2>À propos</h2>
            <p>
                Notre plateforme de gestion d'école est conçue pour simplifier la gestion des emplois du temps, des notes et des utilisateurs.
                Que vous soyez administrateur, enseignant ou élève, vous trouverez ici tous les outils nécessaires pour une gestion efficace.
            </p>
        </div>
    </section>

    <!-- Pied de page -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2023 Gestion d'école. Tous droits réservés.</p>
            <ul class="social-links">
                <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#"><i class="fab fa-linkedin"></i></a></li>
            </ul>
        </div>
    </footer>
</body>
</html>