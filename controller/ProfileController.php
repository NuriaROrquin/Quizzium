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

            $data["owner"] = $this->profileModel->setGenderOnView($data["owner"]);

            $data["owner"] = $this->profileModel->getCantidadDePartidasJugadas($data["owner"], $id_cuenta);

            $data["owner"] = $this->profileModel->getPuntajeMaximoLogrado($data["owner"], $id_cuenta);

            $data["owner"] = $this->profileModel->getPosicionDelRanking($data["owner"], $id_cuenta);

            $_SESSION["owner"] = $data["owner"];

        } else {

            $id_cuenta = $_GET['id_cuenta'];

            $data["public"] = $this->profileModel->getProfile($id_cuenta);

            $data["public"] = $this->profileModel->getCantidadDePartidasJugadas($data["public"], $id_cuenta);

            $data["public"] = $this->profileModel->getPuntajeMaximoLogrado($data["public"], $id_cuenta);

            $data["public"] = $this->profileModel->getPosicionDelRanking($data["public"], $id_cuenta);
        }

        $this->renderer->render('profile', $data);
    }

    public function edit()
    {
        $dataProfile = $_POST;

        $dataProfile = json_encode($_FILES, JSON_UNESCAPED_UNICODE);
        echo $dataProfile;
        exit();

        $dataProfile = json_encode($dataProfile, JSON_UNESCAPED_UNICODE);

        $dataProfile['id_cuenta'] = $_SESSION["owner"]['id_cuenta'];

        $dataProfile['mailExistente'] = true;

        $mailUser = $_SESSION['user'];

        $newMail = $dataProfile['mail'];

        $result = $this->profileModel->checkMail($newMail, $mailUser);

        $verificatePhoto = $this->profileModel->verificateProfilePhoto($dataProfile['newProfilePhoto']);

        if ($result && $verificatePhoto) {

            $dataProfile = $this->profileModel->updateData($dataProfile);
            $_SESSION['user'] = $dataProfile['mail'];
            $dataProfile['mailExistente'] = false;
        }

        $dataProfile = json_encode($dataProfile, JSON_UNESCAPED_UNICODE);
        echo $dataProfile;

    }

    public function changePhoto()
    {
        header("location: /profile/list");
        var_dump($_FILES);
        exit();
    }

}