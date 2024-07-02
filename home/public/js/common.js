(() => {
    "use strict";
    var e, t, s, d, n, c;
    e = document.getElementById("js-navbar-button"), t = document.getElementById("js-sidebar"), s = document.getElementById("js-sidebar-button"), d = "no-scroll", n = "app__sidebar--active", c = function() {
        t.classList.contains(n) ? (document.body.classList.remove(d), t.classList.remove(n)) : (document.body.classList.add(d), t.classList.add(n))
    }, e.addEventListener("click", c), s.addEventListener("click", c)
})();