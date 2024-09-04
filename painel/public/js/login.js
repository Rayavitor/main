(() => {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();

    // Form Handling
    (() => {
        var errorGroup = document.querySelector("#error-group");
        var errorText = document.querySelector("#error-text");

        function displayError(message) {
            if (!message) {
                errorGroup.style.display = "none";
                errorText.innerHTML = "";
            } else {
                errorGroup.style.display = "block";
                errorText.innerHTML = message;
            }
        }

        var submitting = false;
        var signInButton = document.querySelector("#signInButton .button__text");

        function setSubmitting(state) {
            submitting = state;
            if (state) {
                signInButton.innerHTML = "Espere, por favor";
                displayError("");
            } else {
                signInButton.innerHTML = "Logar";
            }
        }

        var loginForm = document.querySelector("#login_form");
        loginForm.addEventListener("submit", function (e) {
            e.preventDefault();
            if (!submitting) {
                var formData = new FormData(loginForm);
                setSubmitting(true);
                fetch("/meuprojeto/painel/engine/user/login.php", {
                    method: "POST",
                    body: formData
                })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    setSubmitting(false);
                    if (!data || !data.answer) {
                        displayError("Problema de conexão com o servidor. Tente depois.");
                        return;
                    }
                    switch (data.answer) {
                        case "not_account":
                        case "not_pass":
                            displayError("Apelido ou senha incorretos. Verifique a exatidão dos dados inseridos.");
                            break;
                        case "not_gcode":
                            displayError("Um código de autenticação inválido foi inserido. Verifique a exatidão dos dados inseridos.");
                            break;
                        case "deactivated":
                            displayError("Sua conta foi desativada.");
                            break;
                        case "captcha":
                            displayError("Você não concluiu o captcha.");
                            break;
                        case "success":
                            window.location = "informacoes.html";
                            break;
                        default:
                            displayError("Resposta inesperada do servidor.");
                            break;
                    }
                })
                .catch(function (error) {
                    setSubmitting(false);
                    displayError("Problema de conexão com o servidor. Tente depois.");
                });
            }
        });
    })();
})();