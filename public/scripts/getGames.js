$(window).on('load', function () {

    $.ajax({
        url: '/lobby/getGames',
        type: 'GET',
        success: function (response) {

            var historialPartidas = JSON.parse(response);

            var listaPartidas = $('#lista-partidas');
            listaPartidas.empty();

           if(historialPartidas != false){

               historialPartidas.forEach(function(data) {

                   var listItem = $('<li>').addClass('box');
                   var puntaje = $('<p>').text('Puntaje: ' + data[0]);
                   var nombreJugador = $('<h3>').text(data[1]);

                   listItem.append(nombreJugador, puntaje);
                   listaPartidas.append(listItem);

               });
           }

           else {

               var listItem = $('<li>').addClass('noHayPartidas');
               var partida = $('<p>').text('Aun no hay partidas jugadas');

               listItem.append(partida);
               listaPartidas.append(listItem);
           }

        },
        error: function () {
            alert('Error al cargar el historial de partidas.');
        }
    });

});