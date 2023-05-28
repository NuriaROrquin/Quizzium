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

            header("location: /lobby/list");
            exit();
        }
        else {
            $data = [];

            if (isset($_SESSION['empty_fields_error']) && $_SESSION['empty_fields_error'] == true) {
                $data['empty_fields_error'] = $_SESSION['empty_fields_error'];
            }

            if (isset($_SESSION["password_error"]) && $_SESSION["password_error"] == true) {
                $data["password_error"] = $_SESSION["password_error"];
            }

            if (isset($_SESSION["mail_error"]) && $_SESSION["mail_error"] == true) {
                $data["mail_error"] = $_SESSION["mail_error"];
            }

            if (isset($_SESSION["photo_error"]) && $_SESSION["photo_error"] == true) {
                $data["photo_error"] = $_SESSION["photo_error"];
            }
            echo "aca muestro el array data:";
            var_dump($data);
            session_unset(); // Eliminas los datos de la sesión
            session_destroy(); // Destruyes la sesión

            if(count($data) == 0){
                $this->renderer->render('register');
            }
            else{
                $this->renderer->render('register', $data);
            }
        }


    }

    public function validate()
    {
        $this->registerModel->validate($_POST['register']);

    }
}