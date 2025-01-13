<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = htmlspecialchars($_POST['mot_de_passe']);

    // Validation des données
    if (empty($email) || empty($mot_de_passe)) {
        showError('Tous les champs sont obligatoires.');
    } else {
        // Vérifier les informations de connexion
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            redirect('dashboard.php');
        } else {
            showError('Email ou mot de passe incorrect.');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion d'école</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="css/css_login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Icônes FontAwesome -->
</head>
<body>

    <main class="dashboard">
        <div class="dashboard-header">
            <h1>Connexion</h1>
            <p>Bienvenue ! Veuillez vous connecter pour accéder à votre compte.</p>
        </div>

        <!-- Affichage des messages d'erreur ou de succès -->
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <fieldset>
                    <legend><i class="fas fa-sign-in-alt"></i> Formulaire de connexion</legend>
                    <form method="POST" class="login-form">
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope"></i> Email :</label>
                            <input type="email" id="email" name="email" placeholder="Entrez votre email" required>
                        </div>

                        <div class="form-group">
                            <label for="mot_de_passe"><i class="fas fa-lock"></i> Mot de passe :</label>
                            <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="Entrez votre mot de passe" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </form>
                </fieldset>

                <div class="login-footer">
                    <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a>.</p>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?> <!-- Inclure le pied de page -->
</body>
</html>