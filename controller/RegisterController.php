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

    private function security()
    {
        $userIsOn = false;

        $fileToCompare = "./config/seguridad.txt";

        $cookie = empty($_COOKIE['seguridad']) ? false : $_COOKIE['seguridad'];

        if (file_exists($fileToCompare) && $cookie == file_get_contents($fileToCompare)) {

            header("location: /lobby/list");
            exit();
        }

        return $userIsOn;
    }

    public function list()
    {
        if ( $this->security() == false ) {

            $data = [];

            if ( isset($_SESSION['empty_fields_error']) && $_SESSION['empty_fields_error'] == true) {
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


            if (count($data) == 0) {
                $this->renderer->render('register');
            } else{
                $this->renderer->render('register', $data ?? "");
                $data = [];
            }
        }
    }

    public function validate()
    {
        if( $this->security() == false){

            if( $this->registerModel->validate($_POST['register']) ){
                header('location: /mail/list?mail=' . urlencode($_POST['register']['mail']));
                exit();
            }
            header("Location: /register/list");
        }
    }
}