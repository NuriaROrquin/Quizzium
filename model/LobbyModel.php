<?php

class LobbyModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getProfile($id_cuenta)
    {
        return $this->database->query("SELECT foto_perfil, usuario, id_cuenta, nombre, apellido, fecha_nacimiento, mail, g.tipo FROM cuenta c JOIN genero g ON c.id_genero = g.id_genero WHERE id_cuenta = '$id_cuenta'");
    }

    public function getID($mail)
    {
        $id = $this->database->querySelectAssoc("SELECT id_cuenta FROM cuenta WHERE mail = '$mail'");
        return $id["id_cuenta"];
    }

    public function getRankingPosition($id_cuenta)
    {
        $statement = $this->database->query("
        SELECT j.id_cuenta, MAX(j.puntaje) AS puntaje_maximo, c.id_cuenta,c.usuario, c.foto_perfil, c.nombre, c.apellido 
        FROM juego j 
        JOIN cuenta c 
        ON j.id_cuenta = c.id_cuenta 
        GROUP BY j.id_cuenta
        ORDER BY puntaje_maximo DESC;");

        $index = 1;

        $data ['rankingPosition'] = "-";

        while ($fila = $statement->fetch_assoc()) {
            $fila['index'] = $index;

            if($id_cuenta == $fila['id_cuenta']){
                $data['rankingPosition'] =$fila['index'];
                break;
            }
            $index++;
        }

        return $data['rankingPosition'];
    }


    public function getGames($id_cuenta,$start, $limit)
    {

        $sql = "SELECT  j.puntaje, c.usuario
                FROM juego j
                JOIN cuenta c 
                ON j.id_cuenta = c.id_cuenta
                WHERE j.id_cuenta = $id_cuenta 
                LIMIT $start, $limit;";

        $dataOfGames = $this->database->querySelectAll($sql);

        if($dataOfGames == null){
            $dataOfGames = false;
        }

        return $dataOfGames;
    }

    public function getAllPlayers($id_cuenta)
    {

        $sql = "SELECT MAX(j.puntaje) as puntaje, c.id_cuenta, c.usuario
                FROM juego j
                JOIN cuenta c 
                ON j.id_cuenta = c.id_cuenta
                WHERE j.id_cuenta <> '$id_cuenta'
                GROUP BY c.id_cuenta, c.usuario";

        $result = $this->database->query($sql);

        $players = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $players[] = $row;
        }

        if($players == null){
            $players = false;
        }

        return $players;
    }

    public function getNumberOfGames($id_cuenta)
    {

        $sql = "SELECT count(id_cuenta) as totalJuegos
                FROM juego
                WHERE id_cuenta = $id_cuenta;";

        $NumberOfGames = $this->database->querySelectAssoc($sql);
        return $NumberOfGames["totalJuegos"];

        if($NumberOfGames == null){
            $NumberOfGames = false;
        }

        return $NumberOfGames;
    }

    public function getRol($id_cuenta){

        $sql = "SELECT `id_rol`FROM `cuenta` WHERE `id_cuenta` = '$id_cuenta';";

        return $this->database->querySelectAssoc($sql)['id_rol'];
    }

    public function setAdminLobbyView($idRol){

        $result = false;

        if($idRol == 1){
            $result = true;
        }

        return $result;
    }

    public function setEditorLobbyView($idRol){

        $result = false;

        if($idRol == 2){
            $result = true;
        }

        return $result;
    }



}