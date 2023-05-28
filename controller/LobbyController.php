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
        if($_COOKIE['seguridad']){

            $this->renderer->render('lobby');
        }else{
            header("location:/login/list");
        }
    }
}