$(window).on('load', function () {
    let totalGamesShownPerPage = 5;
    let ActualPage = 1;
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
                            paginatorHTML += '<li><button class="paginatorButton" style="text-decoration: none;" value="' + i + '">' + i + '</button></li>';
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


                },
                error: function () {
                    alert('Error al cargar el historial de partidas.');
                }
            });
        }

});