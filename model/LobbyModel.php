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

        $sql = "SELECT  j.puntaje, c.usuario, p.multiplayer
                FROM    juego j
                JOIN    cuenta c 
                ON      j.id_cuenta = c.id_cuenta
                JOIN    partida p
                on      j.id_partida = p.id_partida
                WHERE   j.id_cuenta = $id_cuenta 
                AND     p.fue_aceptada = 1
                ORDER BY j.id_juego desc
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

    public function getChallengedGames($id_cuenta)
    {

        $sql = "SELECT      p.`id_partida`, j.`id_desafiador`, c.usuario AS desafiador, j2.puntaje AS resultado
                FROM        partida p 
                INNER JOIN  juego j 
                ON          p.id_partida = j.id_partida 
                INNER JOIN  cuenta c
                ON          c.id_cuenta = j.id_desafiador
                INNER JOIN  juego j2
                ON          j.id_desafiador = j2.id_cuenta
                AND         p.id_partida = j2.id_partida
                WHERE       p.fue_visto = 0 
                AND         p.fue_aceptada = 0 
                AND         j.id_desafiador IS NOT NULL
                AND         j.id_cuenta = $id_cuenta";

        $result = $this->database->query($sql);

        $challengedGames = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $challengedGames[] = $row;
        }

        if($challengedGames == null){
            $challengedGames = false;
        }

        return $challengedGames;
    }

    public function denyChallenge($id_partida, $id_cuenta){
        $sql = "UPDATE `partida` 
                SET     `fue_aceptada`= 0,
                        `fue_visto`= 1
                WHERE   id_partida = $id_partida";

        $result = $this->database->query($sql);

        $sql = "SELECT      j.id_juego, j.id_partida
                FROM        juego j
                INNER JOIN  partida p
                ON          j.id_partida = p.id_partida
                WHERE       j.id_partida = $id_partida
                AND         j.id_cuenta =$id_cuenta
                AND         j.id_desafiador IS NOT NULL
                AND         p.fue_aceptada = 0
                AND         p.fue_visto = 1";

        $id_juego =$this->database->querySelectAssoc($sql)['id_juego'];

        $this->deleteDesafiador($id_juego);

        if($id_juego){
            $result = $id_juego;
        }
        return $result;

        return $result;
    }

    public function deleteDesafiador($id_juego)
    {
        $this->database->query("UPDATE `juego` SET `id_desafiador`= NULL WHERE `id_juego` = " .$id_juego .";");
    }

    public function acceptChallenge($id_partida, $id_cuenta){
        $sql = "UPDATE  `partida` 
                SET     `fue_aceptada`= 1,
                        `fue_visto`= 1
                WHERE   id_partida = $id_partida";

        $this->database->query($sql);

        $sql = "SELECT  id_juego
                FROM    juego
                WHERE   id_partida = $id_partida
                AND     id_cuenta = $id_cuenta;";

        $result = $this->database->querySelectAssoc($sql);

        return $result;
    }

    public function getNumberOfGames($id_cuenta)
    {

        $sql = "SELECT      count(id_cuenta) as totalJuegos
                FROM        juego j
                INNER JOIN  partida p 
                ON          j.id_partida = p.id_partida
                AND         p.fue_aceptada = 1
                WHERE       id_cuenta = $id_cuenta;";

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