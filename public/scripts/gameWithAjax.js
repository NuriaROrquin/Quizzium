$(window).on('load', function () {

    let tiempo = 19;
    let cronometro;

    function iniciarCronometro() {

        cronometro = setInterval(function () {
            $('#cronometro').text(tiempo);
            tiempo--;

            if (tiempo < 0) {
                clearInterval(cronometro);

                let id_question = $('#id_question').val();

                let data = "id_question=" + id_question + "&cronometroEnCero=1";

                $.ajax({
                    url: '/game/answer',
                    type: 'POST',
                    data: data,
                    success: function (response) {

                        var data = JSON.parse(response);

                        finalizarPartida(data);

                    },
                    error: function (xhr, status, error) {
                    }
                });
            }
        }, 1000);
    }

    iniciarCronometro();

    $('.answer').click(function (event) {

        clearInterval(cronometro);
        $('#cronometro').text(20);

        tiempo=19;
        iniciarCronometro();

        event.preventDefault();

        let id_question = $('#id_question').val();
        let selectedOption = $(this).val();

        let data = "id_question=" + id_question + "&selectedOption=" + selectedOption;

        $.ajax({
            url: '/game/answer',
            type: 'POST',
            data: data,
            success: function (response) {

                var data = JSON.parse(response);

                siguientePregunta(data);

                if ( data.mostrarFinalPartida ) {
                    finalizarPartida(data)
                }
            },
            error: function (xhr, status, error) {
            }
        });
    });

    $('.report').click(function (event) {

        event.preventDefault();
        clearInterval(cronometro);
        reportarPregunta();
    });
});


function finalizarPartida(data){

    clearInterval(cronometro);

    $('#form-game').css({'display': 'none'});
    $('.timer').css({'display': 'none'});
    $('.categoria').css({'display': 'none'});
    $('#categoryName').css({'display': 'none'});
    $('.categoryTitle').css({'display': 'none'});
    $('.question').css({'display': 'none'});

    $('#mostrarFinalPartida').css({'display': 'block'});
    $('#puntuacionFinal').text(data.puntuacion);
    $('#textoOpcionCorrecta').text(data.textoOpcionCorrecta);
}

function reportarPregunta(data){

    $('#mostrarReporte').css({'display': 'block'});
}


function siguientePregunta(data){

    $('.puntuacion').text(data.puntuacion);

    $('#categoryName').text(data.categoryName);

    $('#categoryColor').css({'background-color': data.categoryColor});
    $('#categoryName').css({'color': data.categoryColor});

    $('#id_question').val(data.id_question);

    $('#question').text(data.question);

    $('#option_1').text(data.opcion1);
    $('#option_2').text(data.opcion2);
    $('#option_3').text(data.opcion3);
    $('#option_4').text(data.opcion4);

    $('#option_1').val(data.id_opcion1);
    $('#option_2').val(data.id_opcion2);
    $('#option_3').val(data.id_opcion3);
    $('#option_4').val(data.id_opcion4);

}

