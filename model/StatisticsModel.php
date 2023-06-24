<?php

class StatisticsModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getPlayersByCountry()
    {
        $sql = "SELECT pais, COUNT(*) AS cantidad_usuarios FROM cuenta GROUP BY pais;";

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

    private function convertToInt($result)
    {
        foreach ($result as &$dato) {
            $dato[1] = intval($dato[1]);
        }

        return $result;
    }

    public function getPlayersByAge()
    {
        $sql = "SELECT 
        CASE 
        WHEN edad_calculada < 18 THEN 'Menores'
        WHEN edad_calculada >= 18 AND edad_calculada <= 65 THEN 'Medio'
        ELSE 'Jubilados'
        END AS grupo_edad,
        COUNT(*) AS cantidad_usuarios
        FROM cuenta
        GROUP BY grupo_edad;";

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
}