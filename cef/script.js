document.addEventListener('DOMContentLoaded', function() {
    function Alert(type, title, message, time) {
        var number = Math.floor((Math.random() * 1000) + 1);
        $('.notify-wrapper').append(`
        <div class="notify-div wrapper-${number}" style="border-left: 5px solid ${colours[type]}; display:none">
        <div class="align-items-baseline notify-title"><i class="${icons[type]} fa-ms notify-icon" style="color: ${colours[type]}"></i>
            <h5 class="text-uppercase notify-title-text" style="color: ${colours[type]}">${title}</h5>
        </div>
        <p class="text-break notify-main-text">${message}</p>
        </div>`);
        $(`.wrapper-${number}`).fadeIn("slow");
        var sound = new Audio('sound.mp3');
        sound.volume = 0.6;
        sound.play();
        setTimeout(function() {
            $(`.wrapper-${number}`).fadeOut("slow", function() {
                $(`.wrapper-${number}`).remove();
            });
        }, time);
    }

    // Receber eventos do servidor
    cef.on('send:action:notificar', function(data) {
        var eventData = JSON.parse(data);
        Alert(eventData.type, eventData.title, eventData.message, eventData.time);
    });
});
