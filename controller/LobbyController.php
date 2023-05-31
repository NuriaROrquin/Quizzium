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

    public function list()
    {
        $fileToCompare = "./config/seguridad.txt";

        if (file_exists($fileToCompare) && $_COOKIE['seguridad'] == file_get_contents($fileToCompare)) {

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