// Validation du formulaire de connexion
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.querySelector('form');

    if (loginForm) {
        loginForm.addEventListener('submit', function (event) {
            const email = loginForm.querySelector('input[name="email"]').value;
            const password = loginForm.querySelector('input[name="mot_de_passe"]').value;

            if (!email || !password) {
                alert('Veuillez remplir tous les champs.');
                event.preventDefault();
            }
        });
    }

    // Validation du formulaire d'ajout d'étudiant
    const studentForm = document.querySelector('form[action="etudiants.php"]');

    if (studentForm) {
        studentForm.addEventListener('submit', function (event) {
            const nom = studentForm.querySelector('input[name="nom"]').value;
            const email = studentForm.querySelector('input[name="email"]').value;
            const password = studentForm.querySelector('input[name="mot_de_passe"]').value;

            if (!nom || !email || !password) {
                alert('Veuillez remplir tous les champs.');
                event.preventDefault();
            }
        });
    }
});

// Gestion du menu mobile
document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('mobile-menu');
    const mainNav = document.querySelector('.main-nav');

    menuToggle.addEventListener('click', function () {
        mainNav.classList.toggle('active');
    });
});
