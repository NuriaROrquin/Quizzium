<?php

class RankingModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getRanking()
    {
        $results = $this->database->querySelectAll("SELECT id_cuenta, MAX(puntaje) AS puntaje_maximo FROM juego GROUP BY id_cuenta;");

        $recordsWithIndex = array_map(function ($indexResult, $result) {
            foreach ($result as $record){
                return ['index' => $indexResult+1, 'result' => $record];
            }
        }, array_keys($results), $results);

        return $recordsWithIndex;
    }

}