<?php

class StatisticsModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getPlayersByCountry($filters)
    {
        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "SELECT pais, COUNT(*) AS cantidad_usuarios FROM cuenta" . $whereClause . " GROUP BY pais;";

        $result = $this->database->querySelectAll($sql);

        $resultConverted = $this->convertToInt($result);

        $chart['datos'] = $resultConverted;

        $chart['columnas'] = [
            ['string', 'Pais'],
            ['number', 'Total']
        ];

        $chart['title'] = 'Cantidad de usuarios por pais';

        return $chart;
    }

    public function getPlayersByAge($filters)
    {
        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "SELECT 
        CASE 
        WHEN edad_calculada < 18 THEN 'Menores'
        WHEN edad_calculada >= 18 AND edad_calculada <= 65 THEN 'Medio'
        ELSE 'Jubilados'
        END AS grupo_edad,
        COUNT(*) AS cantidad_usuarios
        FROM cuenta
        " . $whereClause . "
        GROUP BY grupo_edad;";

        $result = $this->database->querySelectAll($sql);

        $resultConverted = $this->convertToInt($result);

        $chart['datos'] = $resultConverted;

        $chart['columnas'] = [
            ['string', 'Pais'],
            ['number', 'Total']
        ];

        $chart['title'] = 'Cantidad de usuarios por edad';

        return $chart;
    }

    public function getPlayersByGender($filters)
    {
        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "SELECT 
        CASE
        WHEN id_genero = 1 THEN 'Masculino'
        WHEN id_genero = 2 THEN 'Femenino'
        WHEN id_genero = 3 THEN 'Prefiero no decirlo'
        ELSE 'Desconocido'
        END AS grupo_genero,
        COUNT(*) AS cantidad_usuarios
        FROM cuenta
        " . $whereClause . "
        GROUP BY grupo_genero;";

        $result = $this->database->querySelectAll($sql);

        $resultConverted = $this->convertToInt($result);

        $chart['datos'] = $resultConverted;

        $chart['columnas'] = [
            ['string', 'Pais'],
            ['number', 'Total']
        ];

        $chart['title'] = 'Cantidad de usuarios por género';

        return $chart;
    }


    private function convertToInt($result)
    {
        foreach ($result as &$dato) {
            $dato[1] = intval($dato[1]);
        }

        return $result;
    }

    public function getPlayers()
    {

        $sql = "
        SELECT COUNT(id_cuenta) AS cantidad_de_jugadores
        FROM cuenta ;";

        $result = $this->database->querySelectAssoc($sql);

        return $result['cantidad_de_jugadores'];
    }

    public function getNumberOfGames()
    {

        $sql = "
        SELECT COUNT(id_juego) AS cantidad_de_partidas
        FROM juego ;";

        $result = $this->database->querySelectAssoc($sql);

        return $result['cantidad_de_partidas'];
    }

    public function getNumberOfActiveQuestions()
    {

        $sql = "
        SELECT COUNT(id_pregunta) AS cantidad_de_preguntas_activas
        FROM pregunta 
        WHERE esta_activa = 1;";

        $result = $this->database->querySelectAssoc($sql);

        return $result['cantidad_de_preguntas_activas'];
    }

    public function getNumberOfQuestionsViwed()
    {

        $sql = "
        SELECT COUNT(id_sugerencia) AS cantidad_de_preguntas_vistas
        FROM sugerencia
        WHERE fue_vista=1;";

        $result = $this->database->querySelectAssoc($sql);

        return $result['cantidad_de_preguntas_vistas'];
    }

    public function getNumberOfUnseenQuestions()
    {

        $sql = "
        SELECT COUNT(id_sugerencia) AS cantidad_de_preguntas_sin_ver
        FROM sugerencia
        WHERE fue_vista=0;";

        $result = $this->database->querySelectAssoc($sql);

        return $result['cantidad_de_preguntas_sin_ver'];
    }

    public function getPercentageOfEffectivenessPerUser()
    {

        $sql = "
        SELECT dificultad
        FROM cuenta;";

        $result = $this->database->querySelectAssoc($sql);

        $result = $this->database->querySelectAssoc($sql);

        $users = array();

        foreach ($result as $row) {

            $user = new stdClass();

            $user->usuario = $row['id_cuenta'];

            $user->usuario = $row['usuario'];

            $user->dificultad = $row['dificultad'];

            $users[] = $user;

        }

        return $users;
    }


    public function getNewUsers()
    {
        $fechaActual = date("Y-m-d");

        $fechaHaceTresDias = date("Y-m-d", strtotime("-7 days"));

        $sql = "SELECT * FROM cuenta WHERE fecha_creacion BETWEEN '$fechaHaceTresDias' AND '$fechaActual'";

        $result = $this->database->querySelectAssoc($sql);

        $message="";

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                $message = $message .
                    "<br><p>ID de cuenta: " . $row["id_cuenta"] . "<p><br>" .
                    "<p>Fecha de creación: " . $row["fecha_creacion"] . "</p><br>";
            }
        } else {
            $message = "No se encontraron cuentas nuevas.";
        }

        return $message;
    }
}