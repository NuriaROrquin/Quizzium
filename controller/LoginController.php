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

            if (isset($_SESSION['error'])) {
                $data['contrasenia'] = $_SESSION['error'];
                unset($_SESSION['error']);
                unset($_SESSION['validacion']);
            }

            if(isset($_SESSION['validacion'])){

                if($_SESSION['validacion'] == true){
                    $data['validacionTrue'] = true;
                    unset($_SESSION['validacion']);
                }
                else{
                    $data['validacionFalse'] = true;
                    unset($_SESSION['validacion']);
                }
            }
            $this->renderer->render('login', $data ?? "");
        }

        else{
            header("location: /lobby/list");
            exit();
        }
    }

    public function validate()
    {
        if ($this->loginModel->validate($_POST['login'])) {
            header("Location: /lobby/list");
            exit();
        }
        header("Location: /login/list");
        exit();
    }

    public function validateToken()
    {
        if ($this->security()) {
            header("Location: /lobby/list");
            exit();
        }

        if($this->loginModel->validateToken($_GET['token'])){
            $this->renderer->render('login', $data ?? "");
        }

        header("Location: /login/list");
        exit();

    }
}