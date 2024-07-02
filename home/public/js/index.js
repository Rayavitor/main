(() => {
    "use strict";
    (() => {
        function t(t, o) {
            for (var e = 0; e < o.length; e++) {
                var s = o[e];
                s.enumerable = s.enumerable || !1, s.configurable = !0, "value" in s && (s.writable = !0), Object.defineProperty(t, s.key, s)
            }
        }
        const o = function() {
            function o(t) {
                ! function(t, o) {
                    if (!(t instanceof o)) throw new TypeError("Cannot call a class as a function")
                }(this, o), this.modal = t, this.body = document.body, this.closeButton = this.modal.querySelector(".modal__close"), this.modalActiveClass = "modal--isActive", this.noScrollClass = "no-scroll", this.init()
            }
            var e, s;
            return e = o, (s = [{
                key: "init",
                value: function() {
                    var t = this;
                    this.closeButton.addEventListener("click", (function() {
                        return t.close()
                    }))
                }
            }, {
                key: "open",
                value: function() {
                    this.modal.classList.add(this.modalActiveClass), this.body.classList.add(this.noScrollClass)
                }
            }, {
                key: "close",
                value: function() {
                    this.modal.classList.remove(this.modalActiveClass), this.body.classList.remove(this.noScrollClass)
                }
            }]) && t(e.prototype, s), o
        }();
        var e = document.querySelector("#promoButton"),
            s = new o(document.querySelector("#promoModal"));
        e.addEventListener("click", (function() {
            s.open()
        }))
    })()
})();


setInterval(function() {
    var div = document.getElementById("maxservers");
    var MAX_SERVIDORES = div.textContent;

    for (let i = 0; i < MAX_SERVIDORES; i++) {
        var att = document.getElementById(`JogadoresOnline${i}`);
        const serverInfo = att.textContent.split("/");
        $.post("processar_contagem.php", {contar: i, atual: serverInfo[0]}, function(Information) {
            let opco = `#JogadoresOnline${i}`;
            $(opco).text(Information);

            const myArray = Information.split("/");
            var value = myArray[0];
            let percent = (value / myArray[1]) * 100;
            var element = document.getElementById(`ProgressBar${i}`);
            element.style.width = percent + "%";
        });
    }

    var players = 0;
    for (let i = 0; i < MAX_SERVIDORES; i++) {
        var p = document.getElementById(`JogadoresOnline${i}`);
        const serverInfo = p.textContent.split("/");
        players = players + parseInt(serverInfo[0]);
    }
    let t = document.getElementById("JogadoresTotal");
    $(t).text("Geral on-line: " + players);
}, 10000);


