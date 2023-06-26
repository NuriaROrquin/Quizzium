const accordionItems = document.querySelectorAll('.accordion-item');

accordionItems.forEach(item => {
    const header = item.querySelector('.accordion-header');
    header.addEventListener('click', () => {
        item.classList.toggle('active');
    });
});

$(window).on('load', function () {
    $('.accordion-label').on('click', function (event) {
        getQuestionInfo($(this).attr('name'))
    });

    $('.modify-question').click(function (event) {
        updateQuestionInfo($(this).attr('name'))
    })

    $('.delete-question').click(function (event) {
        deleteQuestion($(this).attr('name'))
    })

    $('.ignore-report').on('click', function (event) {
        ignoreReport($(this).attr('name'))
    });
});

function getQuestionInfo(id) {
    var url = '/report/getInfoPendingQuestion?id=' + encodeURIComponent(id);

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
        document.getElementById("pregunta-report" + element.id_pregunta).value = data[0].pregunta;
        document.getElementById("respuesta1-report" + element.id_pregunta).value = data[0].opcion;
        document.getElementById("respuesta2-report" + element.id_pregunta).value = data[1].opcion;
        document.getElementById("respuesta3-report" + element.id_pregunta).value = data[2].opcion;
        document.getElementById("respuesta4-report" + element.id_pregunta).value = data[3].opcion;

        document.getElementById("answer-one-report" + element.id_pregunta).value = data[0].id_opcion;
        document.getElementById("answer-two-report" + element.id_pregunta).value = data[1].id_opcion;
        document.getElementById("answer-three-report" + element.id_pregunta).value = data[2].id_opcion;
        document.getElementById("answer-four-report" + element.id_pregunta).value = data[3].id_opcion;

        if (element.es_correcta == "1") {
            document.getElementById("correcta-report" + element.id_pregunta).value = index + 1;
        }

        if (index === 0) {
            document.getElementById("categoria-report" + element.id_pregunta).value = element.id_categoria;
            let InitialvalueCategory = parseInt(parseInt(document.getElementById("categoria-report" + element.id_pregunta).value))
            changeCategoryColor(InitialvalueCategory, element.id_pregunta)
        }

        document.getElementById("categoria-report" + element.id_pregunta).onchange = function(event) {
            let valueCategory = parseInt(document.getElementById("categoria-report" + element.id_pregunta).value);
            parseInt(valueCategory);
            changeCategoryColor(valueCategory,element.id_pregunta);


        };
    });
}

function changeCategoryColor(valueCategory, id_pregunta) {
    let divCategory = document.getElementById("accordion-content" + id_pregunta)
    switch (valueCategory) {
        case 1:
            divCategory.style.backgroundColor = '#008639';
            break;
        case 2:
            divCategory.style.backgroundColor = '#BEA821';
            break;
        case 3:
            divCategory.style.backgroundColor = '#DC0000';
            break;
        case 4:
            divCategory.style.backgroundColor = '#0176D2';
            break;
        case 5:
            divCategory.style.backgroundColor = '#FF69B4';
            break;
        case 6:
            divCategory.style.backgroundColor = '#FF9400';
            break;
    }
}

function updateQuestionInfo(id, action) {
    var url = '/report/updateQuestion&id=' + encodeURIComponent(id);

    let createdDate = $('#fecha-creacion-report' + id).val();
    let category = $('#categoria-report' + id).val();
    let title = $('#pregunta-report' + id).val();
    let answerOne = $('#respuesta1-report' + id).val();
    let answerTwo = $('#respuesta2-report' + id).val();
    let answerThree = $('#respuesta3-report' + id).val();
    let answerFour = $('#respuesta4-report' + id).val();
    let correctAnswer = $('#correcta-report' + id).val();
    let idAnswerOne = $('#answer-one-report' + id).val();
    let idAnswerTwo = $('#answer-two-report' + id).val();
    let idAnswerThree = $('#answer-three-report' + id).val();
    let idAnswerFour = $('#answer-four-report' + id).val();

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
                console.log("Se modificó la pregunta")
                setTimeout(function() {
                    window.location.reload("/report/list");
                }, 3000);
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

function deleteQuestion(id){
    var url = '/report/deleteQuestion&id=' + encodeURIComponent(id);

    $.ajax({
        url: url,
        type: 'DELETE',
        success: function (response) {
            var data = JSON.parse(response);

            if (!data['bd-success']) {
                console.log(data['bd-error'])
            } else {
                console.log("Se eliminó la pregunta")
                setTimeout(function() {
                    window.location.reload("/report/list");
                }, 3000);
            }
        },
        error: function (xhr, status, error) {
            console.log(error)
        }
    });
}

function ignoreReport(idQuestion){
    let reportId = $('#id-report' + idQuestion).val();
    var url = '/report/ignoreReport&id_report=' + reportId + "&id_question=" + idQuestion;

    $.ajax({
        url: url,
        type: 'DELETE',
        success: function (response) {
            var data = JSON.parse(response);

            if (!data['bd-success']) {
                console.log(data['bd-error'])
            } else {
                console.log("Se ignoró el reporte")
                setTimeout(function() {
                    window.location.reload("/report/list");
                }, 3000);
            }
        },
        error: function (xhr, status, error) {
            console.log(error)
        }
    });
}