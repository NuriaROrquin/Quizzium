<?php

class LobbyController
{

    private $renderer;
    private $lobbyModel;
    private $profileModel;

    public function __construct($lobbyModel, $renderer, $profileModel)
    {
        $this->renderer = $renderer;
        $this->lobbyModel = $lobbyModel;
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

            $id_cuenta = $this->profileModel->getID($_SESSION['user']);
            $data["owner"] = $this->profileModel->getProfile($id_cuenta);

            $this->renderer->render('lobby', $data);
        } else {
            header("location:/login/list");
            exit();
        }
    }

    public function exit()
    {
        if ($this->lobbyModel->exit()) {
            header("Location: /login/list");
            exit();
        }
        $this->renderer->render('lobby');
    }
}