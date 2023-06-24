$('#sendQuestion').click(function (event) {

    event.preventDefault();

    let category = $('#categoria').val();
    let title = $('#pregunta').val();
    let answerOne = $('#respuesta1').val();
    let answerTwo = $('#respuesta2').val();
    let answerThree = $('#respuesta3').val();
    let answerFour = $('#respuesta4').val();
    let correctAnswer = $('#correcta').val();

    let data = "category=" + category + "&title=" + title + "&answerOne=" + answerOne + "&answerTwo=" + answerTwo + "&answerThree=" + answerThree + "&answerFour=" + answerFour + "&correctAnswer=" + correctAnswer;


    $.ajax({
        url: '/factory/sendQuestion',
        type: 'POST',
        data: data,
        success: function (response) {
            console.log(response);
            var data = JSON.parse(response);

            if (!data['bd-success']) {
                showErrors(data);
            } else {
                $("#form-question").hide();
                $(".container-form").append('<div class="msg-success"><h3>¡Gracias por enviarnos tu pregunta!</h3><p>Pronto la evaluaremos y, si es apta, la verás en el juego.</p> <img src="/public/assets/personajes.webp" /></div>');
            }
        },
        error: function (xhr, status, error) {
        }
    });

});

function showErrors(data) {
    if (data.category) {
        $('#categoria-error').text("Seleccioná una categoría");
    }

    if (data.title) {
        $('#pregunta-error').text("Completa este campo");
    }

    if (data.answerOne) {
        $('#respuesta1-error').text("Completa este campo");
    }

    if (data.answerTwo) {
        $('#respuesta2-error').text("Completa este campo");
    }

    if (data.answerThree) {
        $('#respuesta3-error').text("Completa este campo");
    }

    if (data.answerFour) {
        $('#respuesta4-error').text("Completa este campo");
    }


}
