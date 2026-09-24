const loginForm = document.getElementById("loginForm");

const maintenanceDialog =
    document.getElementById("maintenanceDialog");

const closeDialog =
    document.getElementById("closeDialog");

const dialogOk =
    document.getElementById("dialogOk");


// Quando clicar em "Entrar"
loginForm.addEventListener("submit", function (event) {

    event.preventDefault();

    // Abre o dialog de manutenção
    maintenanceDialog.classList.add("active");

});


// Fechar pelo X
closeDialog.addEventListener("click", function () {

    maintenanceDialog.classList.remove("active");

});


// Fechar pelo botão "Entendi"
dialogOk.addEventListener("click", function () {

    maintenanceDialog.classList.remove("active");

});


// Fechar clicando fora da caixa
maintenanceDialog.addEventListener("click", function (event) {

    if (event.target === maintenanceDialog) {

        maintenanceDialog.classList.remove("active");

    }

});