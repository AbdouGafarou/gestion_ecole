<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    redirect('login.php');
}

// Récupérer le rôle de l'utilisateur
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Icônes FontAwesome -->
</head>
<body>
    <?php include 'includes/header.php'; ?> <!-- Inclure l'en-tête -->

    <main class="dashboard">
        <div class="dashboard-header">
            <h1>Tableau de bord</h1>
            <p>Bienvenue, <?php echo htmlspecialchars($role); ?> !</p>
        </div>

        <section class="dashboard-grid">
            <?php if (hasRole('admin')) : ?>
                <!-- Cartes pour l'administrateur -->
                <div class="dashboard-card">
                    <div class="card-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h2>Gestion des utilisateurs</h2>
                    <p>Ajoutez et gérez les utilisateurs du système.</p>
                    <a href="etudiants.php" class="btn">Accéder <i class="fas fa-arrow-right"></i></a>
                </div>
            <?php endif; ?>

            <?php if (hasRole('admin') || hasRole('enseignant')) : ?>
                <!-- Cartes pour l'administrateur et les enseignants -->
                <div class="dashboard-card">
                    <div class="card-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h2>Gestion des étudiants</h2>
                    <p>Consultez et gérez la liste des étudiants.</p>
                    <a href="gest_eleves.php" class="btn">Accéder <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="dashboard-card">
                    <div class="card-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h2>Gestion des notes</h2>
                    <p>Ajoutez et consultez les notes des étudiants.</p>
                    <a href="notes.php" class="btn">Accéder <i class="fas fa-arrow-right"></i></a>
                </div>
            <?php endif; ?>

            <?php if (hasRole('eleve')) : ?>
                <!-- Cartes pour les élèves -->
                <div class="dashboard-card">
                    <div class="card-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h2>Mes notes</h2>
                    <p>Consultez vos notes et votre progression.</p>
                    <a href="gest_notes.php" class="btn">Accéder <i class="fas fa-arrow-right"></i></a>
                </div>
            <?php endif; ?>

            <div class="dashboard-card">
                <div class="card-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h2>Emploi du temps</h2>
                <p>Consultez votre emploi du temps.</p>
                <a href="emploi_du_temps.php" class="btn">Accéder <i class="fas fa-arrow-right"></i></a>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?> <!-- Inclure le pied de page -->
</body>
</html>