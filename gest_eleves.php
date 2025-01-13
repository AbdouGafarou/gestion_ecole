<?php
session_start();
include 'includes/db.php'; // Inclure la connexion à la base de données
include 'includes/functions.php'; // Inclure les fonctions utiles

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isLoggedIn() || !hasRole('admin')) {
    redirect('login.php'); // Rediriger vers la page de connexion si l'utilisateur n'est pas admin
}

// Ajouter un élève
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = htmlspecialchars($_POST['mot_de_passe']);

    // Validation des données
    if (empty($nom) || empty($email) || empty($mot_de_passe)) {
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

            // Insérer l'élève dans la base de données
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, 'eleve')");
            if ($stmt->execute([$nom, $email, $hashedPassword])) {
                showSuccess('Élève ajouté avec succès !');
            } else {
                showError('Une erreur s\'est produite lors de l\'ajout de l\'élève.');
            }
        }
    }
}

// Modifier un élève
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modifier'])) {
    $id = intval($_POST['id']);
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);

    // Validation des données
    if (empty($nom) || empty($email)) {
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
            // Mettre à jour l'élève dans la base de données
            $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ? WHERE id = ?");
            if ($stmt->execute([$nom, $email, $id])) {
                showSuccess('Élève modifié avec succès !');
            } else {
                showError('Une erreur s\'est produite lors de la modification de l\'élève.');
            }
        }
    }
}

// Supprimer un élève
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['supprimer'])) {
    $id = intval($_POST['id']);

    // Supprimer l'élève de la base de données
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
    if ($stmt->execute([$id])) {
        showSuccess('Élève supprimé avec succès !');
    } else {
        showError('Une erreur s\'est produite lors de la suppression de l\'élève.');
    }
}

// Récupérer la liste des élèves
$stmt = $pdo->query("SELECT id, nom, email FROM utilisateurs WHERE role = 'eleve'");
$eleves = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des élèves</title>
    <link rel="stylesheet" href="css/gest_eleve.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>
    <h1>Gestion des élèves</h1>

    <!-- Formulaire d'ajout d'un élève -->
    <h2>Ajouter un élève</h2>
    <form method="POST">
        <label>Nom :</label>
        <input type="text" name="nom" placeholder="Nom de l'élève" required>

        <label>Email :</label>
        <input type="email" name="email" placeholder="Email de l'élève" required>

        <label>Mot de passe :</label>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>

        <button type="submit" name="ajouter">Ajouter</button>
    </form>

    <!-- Liste des élèves -->
    <h2>Liste des élèves</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eleves as $eleve) : ?>
                <tr>
                    <td><?php echo $eleve['nom']; ?></td>
                    <td><?php echo $eleve['email']; ?></td>
                    <td>
                        <!-- Formulaire pour modifier un élève -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $eleve['id']; ?>">
                            <input type="text" name="nom" value="<?php echo $eleve['nom']; ?>" required>
                            <input type="email" name="email" value="<?php echo $eleve['email']; ?>" required>
                            <button type="submit" name="modifier">Modifier</button>
                        </form>

                        <!-- Formulaire pour supprimer un élève -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $eleve['id']; ?>">
                            <button type="submit" name="supprimer">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>