<?php
session_start();
include 'includes/db.php'; // Inclure la connexion à la base de données
include 'includes/functions.php'; // Inclure les fonctions utiles

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isLoggedIn() || !hasRole('admin')) {
    redirect('login.php'); // Rediriger vers la page de connexion si l'utilisateur n'est pas admin
}

// Ajouter un utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = htmlspecialchars($_POST['mot_de_passe']);
    $role = htmlspecialchars($_POST['role']);

    // Validation des données
    if (empty($nom) || empty($email) || empty($mot_de_passe) || empty($role)) {
        showError('Tous les champs sont obligatoires.');
    } elseif (!validateEmail($email)) {
        showError('Adresse email invalide.');
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            showError('Cet email est déjà utilisé.');
        } else {
            // Hasher le mot de passe
            $hashedPassword = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            // Insérer l'utilisateur dans la base de données
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$nom, $email, $hashedPassword, $role])) {
                showSuccess('Utilisateur ajouté avec succès !');
            } else {
                showError('Une erreur s\'est produite lors de l\'ajout de l\'utilisateur.');
            }
        }
    }
}

// Modifier un utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modifier'])) {
    $id = intval($_POST['id']);
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);

    // Validation des données
    if (empty($nom) || empty($email) || empty($role)) {
        showError('Tous les champs sont obligatoires.');
    } elseif (!validateEmail($email)) {
        showError('Adresse email invalide.');
    } else {
        // Vérifier si l'email existe déjà pour un autre utilisateur
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        if ($stmt->fetch()) {
            showError('Cet email est déjà utilisé par un autre utilisateur.');
        } else {
            // Mettre à jour l'utilisateur dans la base de données
            $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ?, role = ? WHERE id = ?");
            if ($stmt->execute([$nom, $email, $role, $id])) {
                showSuccess('Utilisateur modifié avec succès !');
            } else {
                showError('Une erreur s\'est produite lors de la modification de l\'utilisateur.');
            }
        }
    }
}

// Supprimer un utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['supprimer'])) {
    $id = intval($_POST['id']);

    // Supprimer l'utilisateur de la base de données
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
    if ($stmt->execute([$id])) {
        showSuccess('Utilisateur supprimé avec succès !');
    } else {
        showError('Une erreur s\'est produite lors de la suppression de l\'utilisateur.');
    }
}

// Récupérer la liste des utilisateurs
$stmt = $pdo->query("SELECT id, nom, email, role FROM utilisateurs");
$utilisateurs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="css/eleve.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>
    <h1>Gestion des utilisateurs</h1>

    <!-- Formulaire d'ajout d'un utilisateur -->
    <h2>Ajouter un utilisateur</h2>
    <form method="POST">
        <label>Nom :</label>
        <input type="text" name="nom" placeholder="Nom de l'utilisateur" required>

        <label>Email :</label>
        <input type="email" name="email" placeholder="Email de l'utilisateur" required>

        <label>Mot de passe :</label>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>

        <label>Rôle :</label>
        <select name="role" required>
            <option value="admin">Administrateur</option>
            <option value="enseignant">Enseignant</option>
            <option value="eleve">Élève</option>
        </select>

        <button type="submit" name="ajouter">Ajouter</button>
    </form>

    <!-- Liste des utilisateurs -->
    <h2>Liste des utilisateurs</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $utilisateur) : ?>
                <tr>
                    <td><?php echo $utilisateur['nom']; ?></td>
                    <td><?php echo $utilisateur['email']; ?></td>
                    <td><?php echo $utilisateur['role']; ?></td>
                    <td>
                        <!-- Formulaire pour modifier un utilisateur -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $utilisateur['id']; ?>">
                            <input type="text" name="nom" value="<?php echo $utilisateur['nom']; ?>" required>
                            <input type="email" name="email" value="<?php echo $utilisateur['email']; ?>" required>
                            <select name="role" required>
                                <option value="admin" <?php echo $utilisateur['role'] === 'admin' ? 'selected' : ''; ?>>Administrateur</option>
                                <option value="enseignant" <?php echo $utilisateur['role'] === 'enseignant' ? 'selected' : ''; ?>>Enseignant</option>
                                <option value="eleve" <?php echo $utilisateur['role'] === 'eleve' ? 'selected' : ''; ?>>Élève</option>
                            </select>
                            <button type="submit" name="modifier">Modifier</button>
                        </form>

                        <!-- Formulaire pour supprimer un utilisateur -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $utilisateur['id']; ?>">
                            <button type="submit" name="supprimer">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>