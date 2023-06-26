$(window).on('load', function () {
    let totalGamesShownPerPage = 5;
    let ActualPage = 1;
    let data = [];
        $('#games_quantity').on('change', function () {
            fetchData();
        });

    $(document).on('click', '.paginatorButton', function ()  {
            ActualPage = $(this).attr('value');
            fetchData();
        });



        fetchData();

        function fetchData() {
            totalGamesShownPerPage = $('#games_quantity').val();
            var data = "limit=" + totalGamesShownPerPage + "&page=" + ActualPage;

            $.ajax({
                url: '/lobby/getGames',
                type: 'POST',
                data: data,
                success: function (response) {
                    var historialPartidas = JSON.parse(response);
                    data = historialPartidas.players;
                    console.log(data);


                    var listaPartidas = $('#lista-partidas');
                    listaPartidas.empty();

                    if(historialPartidas != false){

                        historialPartidas.games.forEach(function(data) {

                            var listItem = $('<li>').addClass('box');
                            var puntaje = $('<p>').text('Puntaje: ' + data[0]);
                            var nombreJugador = $('<h3>').text(data[1]);

                            listItem.append(nombreJugador, puntaje);
                            listaPartidas.append(listItem);
                        });


                        document.getElementById("paginator").innerHTML = "Mostrando " + historialPartidas.numbersOfGames + " de " + historialPartidas.numbersOfGames + " registros";

                        var paginatorHTML = '<ul>';

                        for (var i = 1; i <= historialPartidas.pages; i++) {
                            paginatorHTML += '<li style="display: inline-block"><button class="paginatorButton" style="text-decoration: none;" value="' + i + '">' + i + '</button></li>';
                        }

                        paginatorHTML += '</ul>';

                        $('#nav-paginator').html(paginatorHTML);

                    }

                    else {

                        var listItem = $('<li>').addClass('noHayPartidas');
                        var partida = $('<p>').text('Aun no hay partidas jugadas');

                        listItem.append(partida);
                        listaPartidas.append(listItem);
                    }

                    $(document).on('click', '#multiplayer', function (){
                        chooseYourPlayer(data);
                    });


                },
                error: function () {
                    alert('Error al cargar el historial de partidas.');
                }
            });
        }

});

function chooseYourPlayer(data) {

    var chooseYourPlayer = $('<div>').attr('id', 'elegirJugador');


    var overlay = $('<div>').addClass('overlay');

    var popup = $('<div>').addClass('popup');

    var titulo = $('<h2>').text('Elije tu contrinncante:');
    var optionList = $('<div>').attr('id', 'optionList');

    $.each(data, function(index, item) {
        var listItem = $('<div>');
        var clase = $('<p>').attr('class', 'users').text('Usuario:' + item.usuario);
        var puntaje = $('<h4>').text('Puntaje: ' + item.puntaje);
        var button = $('<button>').attr('id', item.id_cuenta).attr('type', 'submit').text('Seleccionar');

        listItem.append(clase);
        listItem.append(puntaje);
        listItem.append(button);
        optionList.append(listItem);

        button.on('click', function() {
            var idContrincante = $(this).attr('id');
            var form = $('<form>').attr({
                method: 'POST',
                action: '/game/list'
            });
            var input = $('<input>').attr({
                type: 'hidden',
                name: 'idContrincante',
                value: idContrincante
            });
            form.append(input);
            $('body').append(form);
            form.submit();
        });
    });

    var lobby = $('<button>').addClass('button button-small').text('Volver al lobby');

    lobby.on('click', function() {
        chooseYourPlayer.remove();

    });

    popup.append(titulo, optionList, lobby);
    chooseYourPlayer.append(overlay,popup);
    $('body').append(chooseYourPlayer);

}