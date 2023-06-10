<?php

class LobbyController
{

    private $renderer;
    private $lobbyModel;

    public function __construct($renderer, $lobbyModel)
    {
        $this->renderer = $renderer;
        $this->lobbyModel = $lobbyModel;
    }

    public function list()
    {

        $mail = $_SESSION['user'];

        $id_cuenta = $this->lobbyModel->getID($mail);

        $data["owner"] = $this->lobbyModel->getProfile($id_cuenta);

        $data["rankingPosition"] = $this->lobbyModel->getRankingPosition($id_cuenta);

        $this->renderer->render('lobby', $data);

    }

    public function exit()
    {

        $fileToDelete = "./config/seguridad.txt";
        setcookie("seguridad", 0, time() - 1800, '/');

        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
            header("Location: /login/list");
            exit();
        } else {
            $this->renderer->render('lobby');
        }
    }
}