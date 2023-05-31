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

    private function unsetErrorsSessions(){
        unset($_SESSION['empty_fields_error']);
        unset($_SESSION["password_error"]);
        unset($_SESSION["mail_error"]);
        unset($_SESSION["photo_error"]);
    }

    private function setErrors($errors){
        $errors['empty_fields_error'] = isset($_SESSION['empty_fields_error']) ?? $_SESSION['empty_fields_error'];
        $errors["password_error"] = isset($_SESSION["password_error"]) ?? $_SESSION["password_error"];
        $errors["mail_error"] = isset($_SESSION['mail_error']) ?? $_SESSION["mail_error"];
        $errors["photo_error"] = isset($_SESSION['photo_error']) ?? $_SESSION["photo_error"];
        return $errors;
    }

    public function list()
    {
        $errors=[];

        if (!$this->security()) {

            $errors = $this->setErrors($errors);

            $this->unsetErrorsSessions();

            if (!$errors['empty_fields_error']) {
                unset($errors['empty_fields_error']);
            }

            if (!$errors['password_error']) {
                unset($errors['password_error']);
            }

            if (!$errors['mail_error']) {
                unset($errors['mail_error']);
            }

            if (!$errors['photo_error']) {
                unset($errors['photo_error']);
            }

            $this->renderer->render('register', $errors );
            unset($errors);
        }
    }


    public function validate()
    {
        $this->unsetErrorsSessions();

        if (!$this->security() && isset($_POST['send'])) {

            if ($this->registerModel->validate($_POST['register'])) {
                header('location: /mail/list?mail=' . urlencode($_POST['register']['mail']));
                exit();
            }
        }
        header('location: /register/list');
        exit();
    }

}