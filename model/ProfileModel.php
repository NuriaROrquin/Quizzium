<?php

class ProfileModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getProfile($id_cuenta)
    {
        $result = $this->database->querySelectAssoc("SELECT foto_perfil, usuario, id_cuenta, nombre, apellido, fecha_nacimiento, pais, ciudad, mail, contrasenia, g.tipo, g.id_genero FROM cuenta c JOIN genero g ON c.id_genero = g.id_genero WHERE id_cuenta = '$id_cuenta'");

        return $result;
    }

    public function setGenderOnView($profileData)
    {

        switch ($profileData['id_genero']) {

            case '1':
                $profileData['masculino'] = true;
                break;

            case '2':
                $profileData['femenino'] = true;
                break;

            default:
                $profileData['otro'] = true;
                break;
        }

        return $profileData;
    }


    public function getID($mail)
    {
        $id = $this->database->querySelectAssoc("SELECT id_cuenta FROM cuenta WHERE mail = '$mail'");
        return $id["id_cuenta"];
    }

    public function getMail($id_cuenta)
    {
        $id = $this->database->querySelectAssoc("SELECT mail FROM cuenta WHERE id_cuenta = '$id_cuenta'");
        return $id["mail"];
    }

    public function getRol($id_cuenta)
    {
        $id = $this->database->querySelectAssoc("SELECT id_rol FROM cuenta WHERE id_cuenta = '$id_cuenta'");
        return $id["id_rol"];
    }

    public function checkMail($newMail, $mailUser)
    {
        $result = false;

        $sql = "SELECT mail FROM cuenta WHERE mail ='$newMail';";

        $checkMailinDatabase = $this->database->querySelectAssoc($sql);


        if ($newMail == $mailUser || $checkMailinDatabase == null) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }


    private function getContrasenia($id_cuenta)
    {

        $sql = "SELECT contrasenia FROM cuenta WHERE id_cuenta = '$id_cuenta' ;";

        return $this->database->querySelectAssoc($sql)['contrasenia'];

    }

    public function updateData($newDataProfile)
    {

        //$newDataProfile['tipo_genero'] = $this->setGenderID($newDataProfile['genero']);
        $id_genero = $newDataProfile['genero'];
        $mail = $newDataProfile['mail'];
        $ciudad = $newDataProfile['ciudad'];
        $pais = $newDataProfile['pais'];
        $usuario = $newDataProfile['usuario'];
        //$foto_perfil = ""; ESTE TENGO QUE VERLO DESPUES COMO CARAJO HAGO EL TEMA DE LA VIEW
        $fecha_nacimiento = $newDataProfile['fecha_nacimiento'];
        $nombre = $newDataProfile['nombre'];
        $apellido = $newDataProfile['apellido'];
        $contrasenia = "";
        $id_cuenta = $newDataProfile['id_cuenta'];


        if ($newDataProfile['contrasenia'] == "") {

            $newDataProfile['contrasenia'] = $this->getContrasenia($id_cuenta);
            $contrasenia = $newDataProfile['contrasenia'];

        } else {
            $contrasenia = md5($newDataProfile['contrasenia']);
        }
        //falta updatear la foto de perfil en la query

        $sql = "UPDATE `cuenta` SET
                        `id_genero`= '$id_genero',
                        `mail`= '$mail',
                        `ciudad`= '$ciudad',
                        `pais`= '$pais',
                        `usuario`= '$usuario',
                        `contrasenia`= '$contrasenia',
                        `fecha_nacimiento`= '$fecha_nacimiento',
                        `nombre`= '$nombre',
                        `apellido`='$apellido'
                        WHERE id_cuenta = '$id_cuenta'";

        $this->database->query($sql);

        return $newDataProfile;
    }

    public function getCantidadDePartidasJugadas( $data , $id_cuenta)
    {
        $cantidadDePartidasJugadas = $this->database->querySelectAssoc("SELECT COUNT(id_cuenta) as  cantidadDePartidas FROM juego WHERE id_cuenta = '$id_cuenta'");


        if($cantidadDePartidasJugadas != null ){
            $data['cantidadDePartidas'] = $cantidadDePartidasJugadas;
        } else{
            $data['cantidadDePartidas'] = 0;
        }

        return $data['cantidadDePartidas'];
    }


    public function getPuntajeMaximoLogrado ( $data , $id_cuenta)
    {
        $puntajeMaximo = $this->database->querySelectAssoc("SELECT MAX(j.puntaje) as puntajeMaximo
                                                            FROM juego j 
                                                            WHERE id_cuenta = '$id_cuenta'
                                                            GROUP BY j.id_cuenta ");

        if($puntajeMaximo != null ){
            $data['puntajeMaximo'] = $puntajeMaximo['puntajeMaximo'];
        } else{
            $data['puntajeMaximo'] = 0;
        }

        return $data;
    }


    public function getPosicionDelRanking($data , $id_cuenta)
    {
        $statement = $this->database->query("SELECT j.id_cuenta, MAX(j.puntaje) AS puntaje_maximo 
                                             FROM juego j 
                                             JOIN cuenta c 
                                             ON j.id_cuenta = c.id_cuenta
                                             GROUP BY j.id_cuenta
                                             ORDER BY puntaje_maximo DESC, id_cuenta DESC;");

        $index = 1;

        while ($fila = $statement->fetch_assoc()) {


            if ($id_cuenta == $fila['id_cuenta']) {
                break;
            }

            $index++;
        }

        $data["posicionDelRanking"] = $index;

        return $data;
    }



}