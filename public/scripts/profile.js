$('#editProfile').click(function (event) {

    event.preventDefault();

    let usuario = $('.campoUnoFormularioPerfil').val();
    let nombre = $('.campoDosFormularioPerfil').val();
    let apellido = $('.campoTresFormularioPerfil').val();
    let fecha_nacimiento = $('.campoCuatroFormularioPerfil').val();
    let genero = $('.campoCincoFormularioPerfil').val();
    let pais = $('.campoSeisFormularioPerfil').val();
    let ciudad = $('.campoSieteFormularioPerfil').val();
    let mail = $('.campoOchoFormularioPerfil').val();

    let data = "usuario=" + usuario +
        "&nombre=" + nombre +
        "&apellido=" + apellido +
        "&fecha_nacimiento=" + fecha_nacimiento +
        "&genero=" + genero +
        "&pais=" + pais +
        "&ciudad=" + ciudad +
        "&mail=" + mail;

    $.ajax({
        url: '/profile/edit',
        type: 'POST',
        data: data,
        success: function (response) {

            var data = JSON.parse(response);

            console.log(data);


        },
        error: function (xhr, status, error) {
        }
    });
});


function mailExistente(data) {
    $('#form-game').css({'display': 'none'});
    $('.timer').css({'display': 'none'});
    $('.categoria').css({'display': 'none'});
    $('#categoryColor').css({'display': 'none'});
    $('#categoryTitle').css({'display': 'none'});

    $('#mostrarFinalPartida').css({'display': 'block'});
    $('#puntuacionFinal').text(data.puntuacion);
    $('#textoOpcionCorrecta').text(data.textoOpcionCorrecta);
}