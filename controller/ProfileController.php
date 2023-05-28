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
        $fileToCompare = "./public/seguridad.txt";

        if (file_exists($fileToCompare) && $_COOKIE['seguridad'] == file_get_contents($fileToCompare)) {

            $id_cuenta= $this->profileModel->getID($_SESSION['user']);

            //soy owner del user: si el id_cuenta es el mismo id que tengo en el session llenar owner
            $data["owner"] = $this->profileModel->getProfile($id_cuenta);

            //no soy owner del user: si el id_cuenta no es el mismo id que tengo en el session llenar public
            //$data["public"] = $this->profileModel->getProfile($id_cuenta);

            $this->renderer->render('profile', $data);
        }

        else {
            header("location:/login/list");
            exit();
        }
    }
}