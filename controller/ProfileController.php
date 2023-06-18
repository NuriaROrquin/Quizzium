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

            $data["owner"] =array_merge($data["owner"], $this->profileModel->setGenderOnView($data["owner"]));

            $_SESSION["owner"] = $data["owner"];

        } else {
            $data["public"] = $this->profileModel->getProfile($_GET['id_cuenta']);
        }

        $this->renderer->render('profile', $data);
    }

    public function edit()
    {
        $dataProfile = $_POST;

        $dataProfile['id_cuenta'] = $_SESSION["owner"]['id_cuenta'];

        $dataProfile['mailExistente'] = true;

        $mailUser = $_SESSION['user'];

        $newMail = $dataProfile['mail'];

        $result = $this->profileModel->checkMail($newMail, $mailUser);

        if($result){
            $dataProfile = $this->profileModel->updateData($dataProfile);
            $_SESSION['user'] = $dataProfile['mail'];
            $dataProfile['mailExistente'] = false;
        }

        $dataProfile = json_encode($dataProfile, JSON_UNESCAPED_UNICODE);
        echo $dataProfile;

    }
}