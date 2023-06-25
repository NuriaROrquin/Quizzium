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

        $idRol = $this->lobbyModel->getRol($id_cuenta);

        $data["editor"] = $this->lobbyModel->setEditorLobbyView($idRol);

        $data["administrador"] = $this->lobbyModel->setAdminLobbyView($idRol);

        $data["owner"] = $this->lobbyModel->getProfile($id_cuenta);

        $data["rankingPosition"] = $this->lobbyModel->getRankingPosition($id_cuenta);

        $this->renderer->render('lobby', $data);
    }

    public function getGames(){

        $id_cuenta = $_SESSION['userID']['id_cuenta'];

        $limit = $_POST['limit'] ?? 5;

        $gamesInfo = $this->lobbyModel->getGames($id_cuenta, $limit);

        $gamesInfo = json_encode($gamesInfo, JSON_UNESCAPED_UNICODE);

        echo $gamesInfo;
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