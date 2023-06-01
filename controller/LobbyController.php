<?php

class LobbyController
{

    private $renderer;
    private $lobbyModel;

    public function __construct($lobbyModel, $renderer)
    {
        $this->renderer = $renderer;
        $this->lobbyModel = $lobbyModel;
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
            $this->renderer->render('lobby');
        }

        else {
            header("location:/login/list");
            exit();
        }
    }

    public function exit()
    {
      if($this->lobbyModel->exit()){
          header("Location: /login/list");
          exit();
      }
        $this->renderer->render('lobby');
    }
}