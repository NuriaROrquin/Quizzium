const accordionItems = document.querySelectorAll('.accordion-item');

accordionItems.forEach(item => {
    const header = item.querySelector('.accordion-header');
    header.addEventListener('click', () => {
        item.classList.toggle('active');
    });
});

$(window).on('load', function () {
    $('.accept').click(function (event) {
        updateQuestionInfo($(this).attr('name'), 'accept')
    })

    $('.accordion-label').on('click', function (event) {
        getQuestionInfo($(this).attr('name'))
    });
});

function getQuestionInfo(id) {

    var url = '/factory/getInfoPendingQuestion?id=' + encodeURIComponent(id);

    $.ajax({
        url: url,
        type: 'GET',
        success: function (response) {
            var data = JSON.parse(response);

            $('.accordion-content').ready(function () {
                setData(data);
            });
        },
        error: function (xhr, status, error) {
        }
    });
}

function setData(data) {
    data.forEach(function (element, index) {
        document.getElementById("pregunta-question" + element.id_pregunta).value = data[0].pregunta;
        document.getElementById("respuesta1-question" + element.id_pregunta).value = data[0].opcion;
        document.getElementById("respuesta2-question" + element.id_pregunta).value = data[1].opcion;
        document.getElementById("respuesta3-question" + element.id_pregunta).value = data[2].opcion;
        document.getElementById("respuesta4-question" + element.id_pregunta).value = data[3].opcion;

        document.getElementById("answer-one-question" + element.id_pregunta).value = data[0].id_opcion;
        document.getElementById("answer-two-question" + element.id_pregunta).value = data[1].id_opcion;
        document.getElementById("answer-three-question" + element.id_pregunta).value = data[2].id_opcion;
        document.getElementById("answer-four-question" + element.id_pregunta).value = data[3].id_opcion;

        if (element.es_correcta == "1") {
            document.getElementById("correcta-question" + element.id_pregunta).value = index + 1;
        }

        if (index === 0) {
            document.getElementById("categoria-question" + element.id_pregunta).value = element.id_categoria;
        }
    });
}

function updateQuestionInfo(id, action) {
    var url = '/factory/updateQuestion&id=' + encodeURIComponent(id);

    let createdDate = $('#fecha-creacion-question' + id).val();
    let category = $('#categoria-question' + id).val();
    let title = $('#pregunta-question' + id).val();
    let answerOne = $('#respuesta1-question' + id).val();
    let answerTwo = $('#respuesta2-question' + id).val();
    let answerThree = $('#respuesta3-question' + id).val();
    let answerFour = $('#respuesta4-question' + id).val();
    let correctAnswer = $('#correcta-question' + id).val();
    let idAnswerOne = $('#answer-one-question' + id).val();
    let idAnswerTwo = $('#answer-two-question' + id).val();
    let idAnswerThree = $('#answer-three-question' + id).val();
    let idAnswerFour = $('#answer-four-question' + id).val();

    let data = "id=" + id +
        "&category=" + category +
        "&title=" + title +
        "&answerOne=" + answerOne +
        "&answerTwo=" + answerTwo +
        "&answerThree=" + answerThree +
        "&answerFour=" + answerFour +
        "&correctAnswer=" + correctAnswer +
        "&createdDate=" + createdDate +
        "&idAnswerOne=" + idAnswerOne +
        "&idAnswerTwo=" + idAnswerTwo +
        "&idAnswerThree=" + idAnswerThree +
        "&idAnswerFour=" + idAnswerFour;

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (response) {
            var data = JSON.parse(response);

            if (!data['bd-success']) {
                showErrors(data, id);
                console.log(data['bd-error'])
            } else {
                window.location.reload("/factory/list");
            }
        },
        error: function (xhr, status, error) {
            console.log(error)
        }
    });
}

function showErrors(data, id) {
    if (data.category) {
        $('#categoria-error-question' + id).text("Seleccioná una categoría");
    }

    if (data.title) {
        $('#pregunta-error-question' + id).text("Completa este campo");
    }

    if (data.answerOne) {
        $('#respuesta1-error-question' + id).text("Completa este campo");
    }

    if (data.answerTwo) {
        $('#respuesta2-error-question' + id).text("Completa este campo");
    }

    if (data.answerThree) {
        $('#respuesta3-error-question' + id).text("Completa este campo");
    }

    if (data.answerFour) {
        $('#respuesta4-error-question' + id).text("Completa este campo");
    }
}