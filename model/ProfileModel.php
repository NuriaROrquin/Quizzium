<?php

class ProfileModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getProfile($id_cuenta) {
        return $this->database->query("SELECT * FROM cuenta WHERE id_cuenta = '$id_cuenta'");
    }

}