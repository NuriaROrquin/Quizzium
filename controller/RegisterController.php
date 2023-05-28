<?php

class RegisterController
{

    private $renderer;
    private $registerModel;

    public function __construct($registerModel, $renderer)
    {
        $this->renderer = $renderer;
        $this->registerModel = $registerModel;
    }

    public function list()
    {
        if(!$_COOKIE['seguridad']){
            $this->renderer->render('register');
        }else{
            header("location:/lobby/list");
        }
    }

    public function validate()
    {
        $this->registerModel->validate($_POST['register']);
    }
}