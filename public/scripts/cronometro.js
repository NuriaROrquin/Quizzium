$(window).on('load', function() {
    let tiempo = 19;

    let cronometro = setInterval(function() {
        $('#cronometro').text(tiempo);
        tiempo--;

        if (tiempo < 0) {
            clearInterval(cronometro);
            let value = $('#cronometro').val();
        }
    }, 1000);
});