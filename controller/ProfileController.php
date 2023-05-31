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

    private function security()
    {
        $userIsOn = false;
        $fileToCompare = "./config/seguridad.txt";
        $cookie = empty($_COOKIE['seguridad']) ? false : $_COOKIE['seguridad'];

        if (file_exists($fileToCompare) && $cookie == file_get_contents($fileToCompare)) {
            $userIsOn = true;
        }
        return $userIsOn;
    }


    public function list()
    {

        if (!$this->security()) {
            header("location:/login/list");
            exit();
        }

        $id_cuenta = $this->profileModel->getID($_SESSION['user']);

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