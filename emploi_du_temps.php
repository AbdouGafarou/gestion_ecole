<?php
session_start();
require 'includes/db.php'; // Inclure la connexion à la base de données
require 'includes/functions.php'; // Inclure les fonctions utiles

// Activer l'affichage des erreurs PHP (à désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    redirect('login.php');
}

// Traitement du formulaire d'ajout de cours
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $classe_id = intval($_POST['classe_id']);
    $matiere_id = intval($_POST['matiere_id']);
    $jour = htmlspecialchars($_POST['jour']);
    $heure = htmlspecialchars($_POST['heure']);

    // Validation des données
    if (empty($classe_id) || empty($matiere_id) || empty($jour) || empty($heure)) {
        showError('Tous les champs sont obligatoires.');
    } else {
        try {
            // Insérer le cours dans la base de données
            $stmt = $pdo->prepare("INSERT INTO emploi_du_temps (classe_id, matiere_id, jour, heure) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$classe_id, $matiere_id, $jour, $heure])) {
                showSuccess('Cours ajouté avec succès !');
            } else {
                showError('Une erreur s\'est produite lors de l\'ajout du cours.');
            }
        } catch (PDOException $e) {
            showError('Erreur de base de données : ' . $e->getMessage());
        }
    }
}

// Récupérer la liste des classes
$classes = $pdo->query("SELECT * FROM classes")->fetchAll();

// Récupérer la liste des matières
$matieres = $pdo->query("SELECT * FROM matieres")->fetchAll();

// Récupérer la liste des emplois du temps
$emplois_du_temps = $pdo->query("
    SELECT e.id, c.nom AS classe, m.nom AS matiere, e.jour, e.heure 
    FROM emploi_du_temps e 
    JOIN classes c ON e.classe_id = c.id 
    JOIN matieres m ON e.matiere_id = m.id
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du temps</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/css_emploi.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="emploi-du-temps">
        <h1>Emploi du temps</h1>

        <!-- Affichage des messages d'erreur ou de succès -->
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <!-- Formulaire d'ajout de cours -->
        <section class="form-section">
            <h2>Ajouter un cours</h2>
            <form method="POST" class="form-cours">
                <div class="form-group">
                    <label for="classe_id">Classe :</label>
                    <select name="classe_id" id="classe_id" required>
                        <?php foreach ($classes as $classe) : ?>
                            <option value="<?php echo $classe['id']; ?>"><?php echo $classe['nom']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="matiere_id">Matière :</label>
                    <select name="matiere_id" id="matiere_id" required>
                        <?php foreach ($matieres as $matiere) : ?>
                            <option value="<?php echo $matiere['id']; ?>"><?php echo $matiere['nom']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jour">Jour :</label>
                    <input type="text" name="jour" id="jour" placeholder="Ex: Lundi" required>
                </div>

                <div class="form-group">
                    <label for="heure">Heure :</label>
                    <input type="time" name="heure" id="heure" required>
                </div>

                <button type="submit" class="btn">Ajouter le cours <i class="fas fa-plus"></i></button>
            </form>
        </section>

        <!-- Liste des emplois du temps -->
        <section class="liste-section">
            <h2>Liste des cours</h2>
            <div class="table-container">
                <table class="table-cours">
                    <thead>
                        <tr>
                            <th>Classe</th>
                            <th>Matière</th>
                            <th>Jour</th>
                            <th>Heure</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($emplois_du_temps as $emploi) : ?>
                            <tr>
                                <td><?php echo $emploi['classe']; ?></td>
                                <td><?php echo $emploi['matiere']; ?></td>
                                <td><?php echo $emploi['jour']; ?></td>
                                <td><?php echo $emploi['heure']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>