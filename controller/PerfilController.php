<?php

class PerfilController
{
    private $renderer;
    private $perfilModel;

    public function __construct($perfilModel, $renderer)
    {
        $this->renderer = $renderer;
        $this->perfilModel = $perfilModel;
    }

    public function list()
    {
        $this->perfilModel->validate($_POST['login']['mail']);
        $this->renderer->render('perfil');
    }

    public function validate()
    {

    }
}