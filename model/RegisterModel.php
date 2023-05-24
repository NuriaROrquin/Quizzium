<?php

class RegisterModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function validate($fields)
    {
        /*foreach ($fields as $field) {
            if(!isset($field)){

            }
        }*/

        $sql = "INSERT INTO `cuenta` (`id_genero`, `mail`, `ciudad`, `pais`, `usuario`, `contrasenia`, `foto_perfil`, `fecha_nacimiento`, `nombre`, `apellido`) VALUES (" . $fields['gender'] . "," . $fields['mail'] . "," . $fields['city'] . "," . $fields['country'] . "," . $fields['username'] . "," . $fields['password'] . ", '' ," . $fields['born_date'] . "," .  $fields['name'] . "," .  $fields['name'] .");";

        $this->database->query($sql);



        echo "bien!";
    }
}