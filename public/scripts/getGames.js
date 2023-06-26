$(window).on('load', function () {
    let totalGamesShownPerPage = 5;
    let ActualPage = 1;
    let data = [];
        $('#games_quantity').on('change', function () {
            fetchData();
        });

    $('.accept-question').click(function (event) {
        console.log("accept")
       acceptChallenge($(this).attr('name'))
    })

    $('.deny-question').click(function (event) {
        console.log("deny")
        denyChallenge($(this).attr('name'))
    })

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

                    console.log(historialPartidas.games)
                    data = historialPartidas.players;


                    var listaPartidas = $('#lista-partidas');
                    listaPartidas.empty();



                    if(historialPartidas != false){


                        historialPartidas.games.forEach(function(data) {

                            var listItem = $('<li>').addClass('box');
                            var puntaje = $('<p>').text('Puntaje: ' + data[0]);
                            var nombreJugador = $('<h3>').text(data[1]).addClass("box-title");
                            var multiplayer;

                            if (data[2] === '1') {
                                console.log('entre')
                                multiplayer = $('<h5>').text('Partida entre jugadores');
                            } else {
                                multiplayer = '';
                            }

                            listItem.append(nombreJugador, puntaje,multiplayer);
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

function denyChallenge(id, action) {
    var url = '/lobby/denyChallenge&id=' + encodeURIComponent(id);

    let data = "id=" + id

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (response) {
            var data = JSON.parse(response);
            console.log(response);

            if (!data) {
                console.log('error')
            } else {
                console.log("Se rechazó el desafío")
                window.location.reload("/lobby/list");
            }
        },
        error: function (xhr, status, error) {
            console.log(error)
        }
    });
}

function acceptChallenge(id, action) {
    var url = '/lobby/acceptChallenge&id=' + encodeURIComponent(id);

    let data = "id=" + id

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (response) {

            var data = JSON.parse(response);
            data.idJuego = data.id

            if (!data) {
                console.log('error')
            } else {
                console.log("se acepto el desafío en la partida " + id)
                window.location.href = '/game/list&idPartida='+id;
            }
        },
        error: function (xhr, status, error) {
            console.log(error)
        }
    });
}

function chooseYourPlayer(data) {

    var chooseYourPlayer = $('<div>').attr('id', 'elegirJugador');

    console.log("entrè")


    var overlay = $('<div>').addClass('overlay');

    var popup = $('<div>').addClass('popup');

    var titulo = $('<h2>').text('Elije tu contrinncante:');
    var optionList = $('<div>').attr('id', 'optionList');

    $.each(data, function(index, item) {
        var listItem = $('<div>');
        var clase = $('<p>').attr('class', 'users').text('Usuario:' + item.usuario);
        var puntaje = $('<h2>').text('Puntaje: ' + item.puntaje);
        var button = $('<button>').attr('id', item.id_cuenta).attr('type', 'submit').attr('class', 'button button-small button-orange mt-1').text('Seleccionar');

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