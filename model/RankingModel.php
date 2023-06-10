<?php

class RankingModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getRanking($id_cuenta)
    {
        $results = $this->database->querySelectAll("SELECT j.id_cuenta, MAX(j.puntaje) AS puntaje_maximo, c.id_cuenta,c.usuario, c.foto_perfil, c.nombre, c.apellido FROM juego j JOIN cuenta c ON j.id_cuenta = c.id_cuenta GROUP BY j.id_cuenta;");

        $recordsWithIndex = array_map(function ($indexResult, $result) use ($id_cuenta) {
            foreach ($result as $id){
                if($id_cuenta == $id){
                    return ['index' => $indexResult+1, 'style' => 'background-color:#FFA500; color: #fff;','result' => $id];
                }else{
                    return ['index' => $indexResult+1, 'result' => $id];
                }
            }
        }, array_keys($results), $results);

        $data["rankingList"] = $recordsWithIndex;

        return $data;
    }

}