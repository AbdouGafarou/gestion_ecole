<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

// Vérifier si l'utilisateur est connecté et est un enseignant ou un administrateur
if (!isLoggedIn() || (!hasRole('enseignant') && !hasRole('admin'))) {
    redirect('login.php');
}

// Ajouter une note
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eleve_id = intval($_POST['eleve_id']);
    $matiere_id = intval($_POST['matiere_id']);
    $note = floatval($_POST['note']);
    $date_note = htmlspecialchars($_POST['date_note']);

    // Validation des données
    if (empty($eleve_id) || empty($matiere_id) || empty($note) || empty($date_note)) {
        showError('Tous les champs sont obligatoires.');
    } else {
        // Insérer la note dans la base de données
        $stmt = $pdo->prepare("INSERT INTO notes (eleve_id, matiere_id, note, date_note) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$eleve_id, $matiere_id, $note, $date_note])) {
            showSuccess('Note ajoutée avec succès !');
        } else {
            showError('Une erreur s\'est produite lors de l\'ajout de la note.');
        }
    }
}

// Récupérer la liste des étudiants
$stmt = $pdo->query("SELECT id, nom FROM utilisateurs WHERE role = 'eleve'");
$eleves = $stmt->fetchAll();

// Récupérer la liste des matières
$stmt = $pdo->query("SELECT * FROM matieres");
$matieres = $stmt->fetchAll();

// Récupérer la liste des notes
$stmt = $pdo->query("SELECT n.id, u.nom AS eleve, m.nom AS matiere, n.note, n.date_note FROM notes n JOIN utilisateurs u ON n.eleve_id = u.id JOIN matieres m ON n.matiere_id = m.id");
$notes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des notes</title>
    <link rel="stylesheet" href="css/css_notes.css">
</head>
<body>
    <main class="emploi-du-temps">
    <h1>Gestion des notes</h1>

    <!-- Formulaire d'ajout de note -->
    <h2>Ajouter une note</h2>
    <form method="POST">
        <label>Élève :</label>
        <select name="eleve_id" required>
            <?php foreach ($eleves as $eleve) : ?>
                <option value="<?php echo $eleve['id']; ?>"><?php echo $eleve['nom']; ?></option>
            <?php endforeach; ?>
        </select>

        <label>Matière :</label>
        <select name="matiere_id" required>
            <?php foreach ($matieres as $matiere) : ?>
                <option value="<?php echo $matiere['id']; ?>"><?php echo $matiere['nom']; ?></option>
            <?php endforeach; ?>
        </select>

        <label>Note :</label>
        <input type="number" step="0.01" name="note" required>

        <label>Date :</label>
        <input type="date" name="date_note" required>

        <button type="submit">Ajouter</button>
    </form>
    </main>

    <!-- Liste des notes -->
    <h2>Liste des notes</h2>
    <table>
        <thead>
            <tr>
                <th>Élève</th>
                <th>Matière</th>
                <th>Note</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($notes as $note) : ?>
                <tr>
                    <td><?php echo $note['eleve']; ?></td>
                    <td><?php echo $note['matiere']; ?></td>
                    <td><?php echo $note['note']; ?></td>
                    <td><?php echo $note['date_note']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>