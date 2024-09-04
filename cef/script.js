document.addEventListener('DOMContentLoaded', function() {
    // Defina seus ícones e cores
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

    // Função para exibir o alerta
    function Alert(type, title, message, time) {
        var number = Math.floor((Math.random() * 1000) + 1);
        var icon = icons[type] || "fas fa-info-circle"; // Default icon
        var colour = colours[type] || "#3498db"; // Default color

        // Adiciona o alerta à página
        $('.notify-wrapper').append(`
        <div class="notify-div wrapper-${number}" style="border-left: 5px solid ${colour}; display:none">
            <div class="align-items-baseline notify-title">
                <i class="${icon} fa-ms notify-icon" style="color: ${colour}"></i>
                <h5 class="text-uppercase notify-title-text" style="color: ${colour}">${title}</h5>
            </div>
            <p class="text-break notify-main-text">${message}</p>
        </div>`);
        
        // Mostra o alerta
        $(`.wrapper-${number}`).fadeIn("slow");

        // Reproduz o som
        var sound = new Audio('sound.mp3');
        sound.volume = 0.6;
        sound.play();

        // Remove o alerta após o tempo especificado
        setTimeout(function() {
            $(`.wrapper-${number}`).fadeOut("slow", function() {
                $(`.wrapper-${number}`).remove();
            });
        }, time);
    }

    // Receber eventos do servidor
    if (typeof cef !== 'undefined') {
        cef.on('send:action:notificar', function(data) {
            try {
                var eventData = JSON.parse(data);
                Alert(eventData.type, eventData.title, eventData.message, eventData.time);
            } catch (e) {
                console.error('Error parsing event data:', e);
            }
        });
    } else {
        console.error('CEF object not found.');
    }
});
