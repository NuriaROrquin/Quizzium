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

}