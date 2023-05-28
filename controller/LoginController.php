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
        $this->renderer->render('login');
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