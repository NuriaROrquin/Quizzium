<?php
class LobbyModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function exit(){

        $fileToDelete = "./public/seguridad.txt";
        if (file_exists($fileToDelete) && $_COOKIE['seguridad'] == file_get_contents($fileToDelete)) {

            setcookie("seguridad", 0, time() - 1800, '/');

            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
            }

            header("Location: /login/list");
            exit();
        }
        header("Location: /login/list");
        exit();
    }
}



