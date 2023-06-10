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
        $statement = $this->database->query("SELECT j.id_cuenta, MAX(j.puntaje) AS puntaje_maximo, c.id_cuenta,c.usuario, c.foto_perfil, c.nombre, c.apellido FROM juego j JOIN cuenta c ON j.id_cuenta = c.id_cuenta GROUP BY j.id_cuenta;");

        $resultados = array();

        $index = 1;

        while ($fila = $statement->fetch_assoc()) {
            $fila['index'] = $index;

            if($id_cuenta == $fila['id_cuenta']){
                $fila['style'] = 'background-color:#FFA500; color: #fff;';
            }

            $resultados[] = $fila;
            $index++;
        }

        $data["rankingList"] = $resultados;

        return $data;
    }

}