<?php
session_start();
include 'includes/db.php'; // Inclure la connexion à la base de données
include 'includes/functions.php'; // Inclure les fonctions utiles

// Vérifier si l'utilisateur est connecté et est un élève
if (!isLoggedIn() || !hasRole('eleve')) {
    redirect('login.php'); // Rediriger vers la page de connexion si l'utilisateur n'est pas un élève
}

// Récupérer l'ID de l'élève connecté
$eleve_id = $_SESSION['user_id'];

// Récupérer les notes de l'élève
$stmt = $pdo->prepare("
    SELECT m.nom AS matiere, n.note, n.date_note 
    FROM notes n
    JOIN matieres m ON n.matiere_id = m.id
    WHERE n.eleve_id = ?
");
$stmt->execute([$eleve_id]);
$notes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes notes</title>
    <link rel="stylesheet" href="css/gest_notes.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>
    <h1>Mes notes</h1>

    <!-- Affichage des messages d'erreur ou de succès -->
    <?php if (isset($_SESSION['error'])) : ?>
        <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])) : ?>
        <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <!-- Liste des notes -->
    <?php if (count($notes) > 0) : ?>
        <table>
            <thead>
                <tr>
                    <th>Matière</th>
                    <th>Note</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $note) : ?>
                    <tr>
                        <td><?php echo $note['matiere']; ?></td>
                        <td><?php echo $note['note']; ?></td>
                        <td><?php echo $note['date_note']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Aucune note disponible pour le moment.</p>
    <?php endif; ?>

    <p><a href="dashboard.php">Retour au tableau de bord</a></p>
</body>
</html>