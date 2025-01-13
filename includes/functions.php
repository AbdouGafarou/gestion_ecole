<?php
/**
 * Fonctions utilitaires pour l'application de gestion d'école.
 */

/**
 * Redirige l'utilisateur vers une page spécifique.
 *
 * @param string $url L'URL de destination.
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Vérifie si l'utilisateur est connecté.
 *
 * @return bool True si l'utilisateur est connecté, sinon False.
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Vérifie si l'utilisateur a un rôle spécifique.
 *
 * @param string $role Le rôle à vérifier (admin, enseignant, eleve).
 * @return bool True si l'utilisateur a le rôle, sinon False.
 */
function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

/**
 * Valide une adresse email.
 *
 * @param string $email L'adresse email à valider.
 * @return bool True si l'email est valide, sinon False.
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Valide un mot de passe.
 *
 * @param string $password Le mot de passe à valider.
 * @return bool True si le mot de passe est valide, sinon False.
 */
function validatePassword($password) {
    return strlen($password) >= 8; // Exemple : au moins 8 caractères
}

/**
 * Hash un mot de passe.
 *
 * @param string $password Le mot de passe à hasher.
 * @return string Le mot de passe hashé.
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Vérifie si un mot de passe correspond à son hash.
 *
 * @param string $password Le mot de passe à vérifier.
 * @param string $hash Le hash à comparer.
 * @return bool True si le mot de passe correspond, sinon False.
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Affiche un message d'erreur.
 *
 * @param string $message Le message d'erreur à afficher.
 */
function showError($message) {
    echo "<div class='error'>$message</div>";
}

/**
 * Affiche un message de succès.
 *
 * @param string $message Le message de succès à afficher.
 */
function showSuccess($message) {
    echo "<div class='success'>$message</div>";
}

?>



