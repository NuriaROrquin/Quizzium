<?php

class LoginController
{

    private $renderer;
    private $loginModel;

    public function __construct($loginModel, $renderer)
    {
        $this->renderer = $renderer;
        $this->loginModel = $loginModel;
    }

    public function list()
    {
        $fileToCompare = "./config/seguridad.txt";

        $cookie = empty($_COOKIE['seguridad']) ? false : $_COOKIE['seguridad'];

        if (file_exists($fileToCompare) && $cookie == file_get_contents($fileToCompare)) {

            header("location: /lobby/list");
            exit();

        }
        else {
            /*
            if (isset($_SESSION['error'])) {
                unset($_SESSION['validacion']);
                $data['contrasenia'] = $_SESSION['error'];
            }

            if (isset($_SESSION['validacion']) && $_SESSION['validacion'] == true) {
                $data['validacionTrue'] = $_SESSION['validacion'];
            }

            if (isset($_SESSION['validacion']) && $_SESSION['validacion'] == false) {
                $data['validacionFalse'] = true;
            }

            LOS ERRORES DESPUES LOS VEMOS*/
            $this->renderer->render('login', $data ?? "");
        }
    }

    public function validate()
    {
        if( $this->loginModel->validate($_POST['login']) ){
            header("Location: /lobby/list");
            exit();
        }
        header("Location: /login/list");
        exit();
    }

    public function validateToken()
    {
        $this->loginModel->validateToken($_GET['token']);
    }
}