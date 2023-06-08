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
        $id_cuenta= $this->profileModel->getID($_SESSION['user']);

        if (empty($_GET['id_cuenta']) || $_GET['id_cuenta'] == $id_cuenta) {
            //soy owner del user: si el id_cuenta es el mismo id que tengo en el session llenar owner
            $data["owner"] = $this->profileModel->getProfile($id_cuenta);
        } else {
            //no soy owner del user: si el id_cuenta no es el mismo id que tengo en el session llenar public
            $data["public"] = $this->profileModel->getProfile($_GET['id_cuenta']);
        }
        $this->renderer->render('profile', $data);
    }
}