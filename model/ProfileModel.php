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
        return $this->database->querySelectAssoc("SELECT foto_perfil, usuario, id_cuenta, nombre, apellido, fecha_nacimiento, pais, ciudad, mail, g.tipo FROM cuenta c JOIN genero g ON c.id_genero = g.id_genero WHERE id_cuenta = '$id_cuenta'");
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

    public function checkMail($newMail, $mailUser){
        $result = false;

        $sql = "SELECT mail FROM cuenta WHERE mail ='$newMail';";

        $checkMailinDatabase = $this->database->querySelectAssoc($sql);


        if($newMail == $mailUser || $checkMailinDatabase == null){
            $result = true;
        }else{
            $result = false;
        }
        return $result;
    }

}