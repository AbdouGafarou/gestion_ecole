<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion d'école</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Icônes FontAwesome -->
</head>
<body>
    <header class="main-header">
        <div class="container">
            <!-- Logo -->
            <div class="logo">
                <a href="index.php">Gestion d'école</a>
            </div>

            <!-- Menu de navigation -->
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> Accueil</a></li>
                    <?php if (isLoggedIn()) : ?>
                        <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                        <li><a href="etudiants.php"><i class="fas fa-user-graduate"></i> Étudiants</a></li>
                        <li><a href="notes.php"><i class="fas fa-clipboard-list"></i> Notes</a></li>
                        <li><a href="emploi_du_temps.php"><i class="fas fa-calendar-alt"></i> Emploi du temps</a></li>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
                    <?php else : ?>
                        <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Connexion</a></li>
                        <li><a href="register.php"><i class="fas fa-user-plus"></i> Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <!-- Bouton pour le menu mobile -->
            <div class="menu-toggle" id="mobile-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>