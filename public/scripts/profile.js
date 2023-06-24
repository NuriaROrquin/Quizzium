$('#editProfile').click(function (event) {

    event.preventDefault();

    let usuario = $('#campoUnoFormularioPerfil').val();
    let contrasenia = $('#campoDosFormularioPerfil').val();
    let nombre = $('#campoTresFormularioPerfil').val();
    let apellido = $('#campoCuatroFormularioPerfil').val();
    let fecha_nacimiento = $('#campoCincoFormularioPerfil').val();
    let genero = $('#campoSeisFormularioPerfil').val();
    let pais = $('#campoSieteFormularioPerfil').val();
    let ciudad = $('#campoOchoFormularioPerfil').val();
    let mail = $('#campoNueveFormularioPerfil').val();

    let data = "usuario=" + usuario +
        "&contrasenia=" + contrasenia +
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

            $('#nombreDeUsuario').text(data.usuario);

            mailExistente(data)

        },
        error: function (xhr, status, error) {
        }
    });


});


function mailExistente(data) {

    $('#operacionExitosa').css({
        'display': 'none',
    });

    $('#mailExistente').css({
        'display': 'none',
    });

    if (data.mailExistente == true) {

        $('#campoNueveFormularioPerfil').css({
            'border-color': 'red',
            'color': 'red'
        });

        $('#mailExistente').css({
            'display': 'block',
            'color': 'red'
        });

        $('#operacionExitosa').css({
            'display': 'none',
        });

    }
    else {
        $('#campoNueveFormularioPerfil').css({
            'border-color': '#FFA500',
            'color': '#FFA500'
        });

        $('#mailExistente').css({
            'display': 'none',
        });

        $('#operacionExitosa').css({
            'display': 'block',
            'background-color': '#dff0d8',
            'color': '#3c763d',
            'padding': '10px',
            'margin-bottom': '10px',
            'font-size': '16px',
            'text-align': 'center'
        });
    }

}