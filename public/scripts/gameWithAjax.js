$(document).ready(function() {

    $('.answer').click(function(event) {

        event.preventDefault();

        let id_question = $('#id_question').val();
        let selectedOption = $(this).val();

        let data = "id_question="+id_question+"&selectedOption="+selectedOption;

        $.ajax({
            url: '/game/answer',
            type: 'POST',
            data: data,
            success: function(response) {
                $('#respuesta').html(response);

            },
            error: function(xhr, status, error) {

            }
        });
    });
});
