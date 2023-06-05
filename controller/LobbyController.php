<?php

class LobbyController
{

    private $renderer;
    private $profileModel;

    public function __construct($renderer, $profileModel)
    {
        $this->renderer = $renderer;
        $this->profileModel = $profileModel;
    }

    private function security()
    {
        $userIsOn = false;
        $fileToCompare = "./config/seguridad.txt";
        $cookie = empty($_COOKIE['seguridad']) ? false : $_COOKIE['seguridad'];

        if (file_exists($fileToCompare) && $cookie == file_get_contents($fileToCompare)) {
            $userIsOn = true;
        }
        return $userIsOn;
    }

    public function list()
    {
        if ($this->security()) {

            $mail = $_SESSION['user'];

            $id_cuenta = $this->profileModel->getID($mail);

            $data["owner"] = $this->profileModel->getProfile($id_cuenta);

            $this->renderer->render('lobby', $data);
        }

        else {
            header("location:/login/list");
            exit();
        }
    }

    public function exit()
    {

        $fileToDelete = "./config/seguridad.txt";
        setcookie("seguridad", 0, time() - 1800, '/');

        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
            header("Location: /login/list");
            exit();
        }
        else{
            $this->renderer->render('lobby');
        }
    }
}