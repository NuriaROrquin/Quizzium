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

        tiempo = 19;
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

                if (data.mostrarFinalPartida) {
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

function finalizarPartida(data) {

    clearInterval(cronometro);

    $('#form-game').css({'display': 'none'});
    $('.timer').css({'display': 'none'});
    $('.categoria').css({'display': 'none'});
    $('#categoryName').css({'display': 'none'});
    $('.categoryTitle').css({'display': 'none'});
    $('.question').css({'display': 'none'});

    var mostrarFinalPartida = $('<div>').attr('id', 'mostrarFinalPartida');

    var overlay = $('<div>').addClass('overlay');

    var popup = $('<div>').addClass('popup');

    var titulo = $('<h1>').text('Perdiste!');

    var textoUno = $('<p>').addClass('emphasis').text('La respuesta correcta era:');

    var textoOpcionCorrecta = $('<p>').attr('id', 'textoOpcionCorrecta').text(data.textoOpcionCorrecta);

    var textoDos = $('<p>').addClass('emphasis').text('Tu puntaje fue:');

    var puntuacionFinal = $('<p>').addClass('emphasis').attr('id', 'puntuacionFinal').text(data.puntuacion);

    var lobby = $('<a>').attr('href', '/lobby/list').addClass('button button-small').text('Volver al lobby');

    popup.append(titulo, textoUno, textoOpcionCorrecta, textoDos, puntuacionFinal, lobby);

    mostrarFinalPartida.append(overlay, popup);

    $('body').append(mostrarFinalPartida);

}

function reportarPregunta() {

    id_question = $('#id_question').val();
    question = $('#question').text();

    var formReportar = $('<form>').attr({
        id: 'mostrarReporte',
        method: 'POST',
        action: '/game/reportarPregunta',
        enctype: 'multipart/form-data'
    });

    var overlay = $('<div>').addClass('overlay');

    var popup = $('<div>').addClass('popup');

    var textoUno = $('<p>').addClass('emphasis').text('Contanos que está mal en la pregunta');

    var preguntaAReportar = $('<p>').attr('id', 'preguntaAReportar').text(question);

    var inputIdQuestion = $('<input>').attr({
        type: 'hidden',
        id: 'id_question',
        name: 'idQuestion',
        value: id_question
    });

    var descripcionReporte = $('<textarea>').attr('name', 'reportText').attr('placeholder', 'Ingrese su reporte aquí');

    var botonEnviarReporte = $('<button>').attr('type', 'submit').addClass('button button-small').text('Reportar' + ' pregunta');

    popup.append(textoUno, preguntaAReportar, inputIdQuestion, descripcionReporte, botonEnviarReporte);

    formReportar.append(overlay, popup);

    $('body').append(formReportar);

}


function siguientePregunta(data) {

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

