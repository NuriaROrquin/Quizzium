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
        $this->renderer->render('register');
    }

    public function validate()
    {
        $this->registerModel->validate($_POST['register']);
    }
}