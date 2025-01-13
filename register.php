<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

// Générer un token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier le token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        showError('Token CSRF invalide.');
    } else {
        // Vérifier si toutes les clés existent dans $_POST
        if (isset($_POST['nom'], $_POST['email'], $_POST['mot_de_passe'], $_POST['role'])) {
            $nom = htmlspecialchars($_POST['nom']);
            $email = htmlspecialchars($_POST['email']);
            $mot_de_passe = htmlspecialchars($_POST['mot_de_passe']);
            $role = htmlspecialchars($_POST['role']);

            // Validation des données
            if (empty($nom) || empty($email) || empty($mot_de_passe) || empty($role)) {
                showError('Tous les champs sont obligatoires.');
            } elseif (!validateEmail($email)) {
                showError('Adresse email invalide.');
            } elseif (!validatePassword($mot_de_passe)) {
                showError('Le mot de passe doit contenir au moins 8 caractères.');
            } else {
                // Vérifier si l'email existe déjà
                $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    showError('Cet email est déjà utilisé.');
                } else {
                    // Hasher le mot de passe
                    $hashedPassword = hashPassword($mot_de_passe);

                    // Insérer l'utilisateur dans la base de données
                    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
                    if ($stmt->execute([$nom, $email, $hashedPassword, $role])) {
                        showSuccess('Inscription réussie ! Vous pouvez maintenant vous connecter.');
                        header('Location: login.php');
                        exit;
                    } else {
                        showError('Une erreur s\'est produite lors de l\'inscription.');
                    }
                }
            }
        } else {
            showError('Tous les champs du formulaire doivent être remplis.');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="css/css_register.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Inscription</h1>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" placeholder="Votre nom" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" placeholder="Votre email" required>

            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="Mot de passe (8 caractères minimum)" minlength="8" required>

            <label for="role">Rôle :</label>
            <select id="role" name="role" required>
                <option value="admin">Administrateur</option>
                <option value="enseignant">Enseignant</option>
                <option value="eleve">Élève</option>
            </select>

            <button type="submit">S'inscrire</button>
        </form>

        <p>Déjà inscrit ? <a href="login.php">Connectez-vous ici</a>.</p>
    </div>
</body>
</html>