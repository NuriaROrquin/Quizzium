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

        $data["challengedGames"] = $this->lobbyModel->getChallengedGames($id_cuenta);

        $this->renderer->render('lobby', $data);
    }

    public function getGames(){

        $id_cuenta = $_SESSION['userID']['id_cuenta'];

        $limit = $_POST['limit'] ?? 5;

        $page = $_POST['page'] ?? 0;

        if(!$page){
            $start = 0;
            $page = 1;// creo que esto se puede borrar
        }else{
            $start = ($page-1)*$limit;
        }

        $gamesInfo["players"] = $this->lobbyModel->getAllPlayers($id_cuenta);
        $gamesInfo['numbersOfGames'] = $this->lobbyModel->getNumberOfGames($id_cuenta);

        if( $gamesInfo['numbersOfGames'] > 0){
            $gamesInfo['pages']= ceil($gamesInfo['numbersOfGames'] / $limit);
        }

        $gamesInfo['games'] = $this->lobbyModel->getGames($id_cuenta, $start, $limit);

        $gamesInfo = json_encode($gamesInfo, JSON_UNESCAPED_UNICODE);

        echo $gamesInfo;
    }

    public function denyChallenge()
    {

        $id_partida = $_POST['id'] ?? "";
        $id_cuenta = $_SESSION['userID']['id_cuenta'];

        $denyChallenge = $this->lobbyModel->denyChallenge($id_partida,$id_cuenta);

        $data = json_encode($denyChallenge, JSON_UNESCAPED_UNICODE);

        echo $data;
    }

    public function acceptChallenge()
    {
        $id_partida = $_POST['id'] ?? "";
        $id_cuenta = $_SESSION['userID']['id_cuenta'];

        $acceptChallenge = $this->lobbyModel->acceptChallenge($id_partida, $id_cuenta);

        $data = json_encode($acceptChallenge, JSON_UNESCAPED_UNICODE);

        echo $data;
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