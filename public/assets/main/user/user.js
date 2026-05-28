/**
 * Script de validation de l'enregistrement utilisateur
 */
$('#register-user').click(function () {
    var firstname = $('#firstname').val().trim();
    var lastname = $('#lastname').val().trim();
    var email = $('#email').val().trim();
    var password = $('#password').val();
    var password_confirm = $('#password-confirm').val();
    var passwordLength = password.length;
    var agreeterms = $('#agreeterms');

    // Effacer les messages d'erreur précédents
    $('#error-register-firstname').text("");
    $('#error-register-lastname').text("");
    $('#error-register-email').text("");
    $('#error-register-password').text("");
    $('#error-register-password-confirm').text("");
    $('#error-register-agreeterms').text("");

    var isValid = true;

    // Validation du prénom
    var nameRegex = /^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/;
    if (firstname === "" || !nameRegex.test(firstname)) {
        $('#firstname').addClass('is-invalid').removeClass('is-valid');
        $('#error-register-firstname').text("First Name is not valid");
        isValid = false;
    } else {
        $('#firstname').removeClass('is-invalid').addClass('is-valid');
    }

    // Validation du nom
    if (lastname === "" || !nameRegex.test(lastname)) {
        $('#lastname').addClass('is-invalid').removeClass('is-valid');
        $('#error-register-lastname').text("Last Name is not valid");
        isValid = false;
    } else {
        $('#lastname').removeClass('is-invalid').addClass('is-valid');
    }

    // Validation de l'email
    var emailRegex = /^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/;
    if (email === "" || !emailRegex.test(email)) {
        $('#email').addClass('is-invalid').removeClass('is-valid');
        $('#error-register-email').text("Your email address is not valid");
        isValid = false;
    } else {
        $('#email').removeClass('is-invalid').addClass('is-valid');
    }

    // Validation du mot de passe
    if (passwordLength < 8) {
        $('#password').addClass('is-invalid').removeClass('is-valid');
        $('#error-register-password').text("Your password must be at least 8 characters");
        isValid = false;
    } else {
        $('#password').removeClass('is-invalid').addClass('is-valid');
    }

    // Validation de la confirmation du mot de passe
    if (password !== password_confirm || password_confirm === "") {
        $('#password-confirm').addClass('is-invalid').removeClass('is-valid');
        $('#error-register-password-confirm').text("Your password must be identical!");
        isValid = false;
    } else {
        $('#password-confirm').removeClass('is-invalid').addClass('is-valid');
    }

    // Validation de l'accord aux termes
    if (!agreeterms.is(':checked')) {
        $('#agreeterms').addClass('is-invalid').removeClass('is-valid');
        $('#error-register-agreeterms').text("You should agree to our terms and conditions");
        isValid = false;
    } else {
        $('#agreeterms').removeClass('is-invalid').addClass('is-valid');
    }

    // Si tout est valide
    if (isValid) {
        alert("Your information has been successfully registered!");
        console.log("Formulaire validé. Prêt à soumettre.");
        // Tu peux ici appeler une fonction pour envoyer les données via Ajax ou soumettre un formulaire.
    }
});
