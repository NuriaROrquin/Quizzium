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
        $fileToCompare = "./public/seguridad.txt";

        $cookie = empty($_COOKIE['seguridad']) ? false : $_COOKIE['seguridad'];

        if (file_exists($fileToCompare) && $cookie == file_get_contents($fileToCompare)) {

            header("location:/lobby/list");

        } else {
            $this->renderer->render('register');
        }
    }

    public function validate()
    {
        $this->registerModel->validate($_POST['register']);
    }
}