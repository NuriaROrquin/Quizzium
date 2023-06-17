<?php

class ProfileController
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

        $id_cuenta = $this->profileModel->getID($_SESSION['user']);

        if (empty($_GET['id_cuenta']) || $_GET['id_cuenta'] == $id_cuenta) {
            $data["owner"] = $this->profileModel->getProfile($id_cuenta);
            $_SESSION['owner'] = $data["owner"];
        } else {
            $data["public"] = $this->profileModel->getProfile($_GET['id_cuenta']);
        }

        $this->renderer->render('profile', $data);
    }

    public function edit()
    {
        $dataPerfil = $_POST;

        $mailUser = $_SESSION['user'];

        $newMail = $dataPerfil['mail'];

        $result = $this->profileModel->checkMail($newMail, $mailUser);

        if($result){

          // $this->profileModel->updateData($dataPerfil);

        } else {

            $data['mailExistente'] = true;
        }

        $data = json_encode($dataPerfil, JSON_UNESCAPED_UNICODE);

        echo $result;

    }
}