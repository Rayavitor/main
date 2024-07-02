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

    (() => {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
            }
        }
    
        var n = document.querySelector("#error-group"),
            i = document.querySelector("#error-text");

        function c(e) {
            if (!e) return n.style.display = "none", void(i.innerHTML = "");
            n.style.display = "", i.innerHTML = e
        }
        var s = !1,
            r = document.querySelector("#signInButton .button__text");

        function u(e) {
            s = e, e ? (r.innerHTML = "Espere, por favor", c("")) : (r.innerHTML = "Logar")
        }

        var l = document.querySelector("#login_form");
        l.addEventListener("submit", (function(e) {
            if ((e.preventDefault(), !s)) {
                var t = new FormData(l);
                u(!0), fetch("/painel/engine/user/login.php", {
                    method: "POST",
                    body: t
                }).then((function(e) {
                    return e.json()
                })).then((function(e) {
                    if (u(!1), !e || !e.answer) return c("Problema de conexÃ£o com o servidor. Tente depois.");
                    switch (e.answer) {
                        case "not_account":
                        case "not_pass":
                            c("Apelido ou senha incorretos. Verifique a exatidÃ£o dos dados inseridos.");
                            break;
                        case "not_gcode":
                            c("Um cÃ³digo de autenticaÃ§Ã£o invÃ¡lido foi inserido. Verifique a exatidÃ£o dos dados inseridos.");
                            break;
                        case "deactivated":
                            c("Sua conta foi desativada.");
                            break;
                        case "captcha":
                            c("VocÃª nÃ£o concluiu o captcha.")
                            break;
                        case "sucess":
                            window.location = "/painel/";
                            break;
                    }
                })).catch((function(e) {
                    u(!1), c("Problema de conexÃ£o com o servidor. Tente depois.")
                }))
            }
        }))
    })()
})();