<?php

class PerfilModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }


    private function validateMailOnDatabase($mail)
    {

    }


    public function validate()
    {
        echo "aca es el model perfil";
        var_dump($_POST['login']);
        exit;
    }
}