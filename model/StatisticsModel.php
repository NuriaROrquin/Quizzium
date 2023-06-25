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

        //ver el tema de la query de pais, porque ahora lo deberiamos sacar por latitud y longitud

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

    public function getPlayers($filters)
    {

        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "
        SELECT COUNT(id_cuenta) AS cantidad_de_jugadores
        FROM cuenta ". $whereClause .";";

        $result = $this->database->querySelectAssoc($sql);

        return $result['cantidad_de_jugadores'];
    }

    public function getNumberOfGames($filters)
    {

        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "
        SELECT COUNT(id_juego) AS cantidad_de_partidas
        FROM juego ". $whereClause .";";

        $result = $this->database->querySelectAssoc($sql);

        return $result['cantidad_de_partidas'];
    }

    public function getNumberOfActiveQuestions($filters)
    {
        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " AND fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "
        SELECT COUNT(id_pregunta) AS cantidad_de_preguntas_activas
        FROM pregunta 
        WHERE esta_activa = 1 " . $whereClause . " ;";

        $result = $this->database->querySelectAssoc($sql);

        return $result['cantidad_de_preguntas_activas'];
    }

    public function getNumberOfViwedSuggestions($filters)
    {

        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " AND fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "
        SELECT COUNT(id_sugerencia) AS cantidad_de_sugerencias_vistas
        FROM sugerencia
        WHERE fue_visto = 1 " . $whereClause . " ;";

        $result = $this->database->querySelectAssoc($sql);

        return $result['cantidad_de_sugerencias_vistas'];
    }

    public function getNumberOfTotalSuggestions($filters)
    {

        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "
        SELECT COUNT(id_sugerencia) AS cantidad_de_sugerencias
        FROM sugerencia ". $whereClause .";";

        $result = $this->database->querySelectAssoc($sql);

        return $result['cantidad_de_sugerencias'];
    }

    public function getPercentageOfEffectivenessPerUser($filters)
    {
        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "
        SELECT id_cuenta , usuario , dificultad
        FROM cuenta ". $whereClause .";";

        $result = $this->database->querySelectAll($sql);

        $usersData = array();

        foreach ($result as $row) {

            $userData = array(
                'id_cuenta' => $row[0],
                'usuario' => $row[1],
                'dificultad' => $row[2]
            );

            $usersData[] = $userData;
        }


        return $usersData;
    }

    public function getNewUsers($filters)
    {
        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "SELECT id_cuenta, usuario, fecha_creacion  FROM cuenta ". $whereClause.";";

        $result = $this->database->querySelectAll($sql);

        $usersData = array();

        foreach ($result as $row) {

            $userData = array(
                'id_cuenta' => $row[0],
                'usuario' => $row[1],
                'fecha_creacion' => $row[2]
            );
            $usersData[] = $userData;
        }

        return $usersData;
    }
}