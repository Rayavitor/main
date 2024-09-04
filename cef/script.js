// Função para criar e exibir notificações
function Alert(type, title, message, time) {
    // Cores e ícones para os tipos de notificação
    var icons = {
        "geral" : "fas fa-keyboard",
        "info" : "fas fa-info-circle",
        "sucesso" : "fas fa-check-circle",
        "erro" : "fas fa-server",
        "aviso" : "fas fa-exclamation-triangle",
        "sms" : "fas fa-sms"
    };

    var colours = {
        "geral" : "#95a5a6",
        "info" : "#3498db",
        "sucesso" : "#1abc9c",
        "erro" : "#dc3545",
        "aviso" : "#f1c40f",
        "sms" : "#e67e22"
    };

    // Gerar um identificador único para a notificação
    var number = Math.floor((Math.random() * 1000) + 1);

    // Adicionar a notificação ao DOM
    $('.notify-wrapper').append(`
        <div class="notify-div wrapper-${number}" style="border-left: 5px solid ${colours[type]}; display:none">
        <div class="align-items-baseline notify-title">
            <i class="${icons[type]} fa-ms notify-icon" style="color: ${colours[type]}"></i>
            <h5 class="text-uppercase notify-title-text" style="color: ${colours[type]}">${title}</h5>
        </div>
        <p class="text-break notify-main-text">${message}</p>
        </div>`);

    // Exibir a notificação
    $(`.wrapper-${number}`).fadeIn("slow");

    // Ocultar e remover a notificação após um tempo
    setTimeout(function () {
        $(`.wrapper-${number}`).fadeOut("slow", function () {
            $(`.wrapper-${number}`).remove();
        });
    }, time);
}

// Configuração do evento de notificação do CEF
cef.on("send:action:notificar", (type, title, message, time) => {
    Alert(type, title, message, time);
});
