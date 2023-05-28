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
        if(isset($_SESSION['error'])){
            $data['contrasenia'] = $_SESSION['error'];
        }

        if(isset($_SESSION['validacion'])){
            $data['validacion'] = $_SESSION['validacion'];
        }
        $this->renderer->render('login', $data);
    }

    public function validate()
    {
        $this->loginModel->validate($_POST['login']);
    }

    public function validateToken()
    {
        $this->loginModel->validateToken($_GET['token']);
    }
}