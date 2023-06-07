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

    public function list()
    {

        $mail = $_SESSION['user'];

        $id_cuenta = $this->profileModel->getID($mail);

        $data["owner"] = $this->profileModel->getProfile($id_cuenta);

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