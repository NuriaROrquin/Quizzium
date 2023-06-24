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
        $errors = [];

        if (isset($_SESSION['empty_fields_error'])) {
            $errors['empty_fields_error'] = true;
        }

        if (isset($_SESSION['password_error'])) {
            $errors['password_error'] = true;
        }

        if (isset($_SESSION['mail_error'])) {
            $errors['mail_error'] = true;
        }

        if (isset($_SESSION['photo_error'])) {
            $errors['photo_error'] = true;
        }

        $this->renderer->render('register', $errors);
        $this->unsetErrorsSessions();
    }

    public function validate()
    {
        $this->unsetErrorsSessions();

        if (isset($_POST['send'])) {

            $datosIngresadosPorElUsuario = $_POST['register'];

            $result = $this->registerModel->validate($datosIngresadosPorElUsuario);

            if (empty($result)) {
                header('location: /mail/list&mail=' . urlencode($_POST['register']['mail']));
                exit();
            } else {
                $this->setErrors($result);
            }
        }
        header('location: /register/list');
        exit();
    }

    private function setErrors($errores)
    {

        if (isset($errores['empty_fields_error'])) {
            $_SESSION['empty_fields_error'] = $errores['empty_fields_error'];
        } else {
            unset($_SESSION['empty_fields_error']);
        }

        if (isset($errores["password_error"])) {
            $_SESSION["password_error"] = $errores["password_error"];
        } else {
            unset($_SESSION["password_error"]);
        }

        if (isset($errores["mail_error"])) {
            $_SESSION["mail_error"] = $errores["mail_error"];
        } else {
            unset($_SESSION["mail_error"]);
        }

        if (isset($errores["photo_error"])) {
            $_SESSION["photo_error"] = $errores["photo_error"];
        } else {
            unset($_SESSION["photo_error"]);
        }
    }

    private function unsetErrorsSessions()
    {
        unset($_SESSION['empty_fields_error']);
        unset($_SESSION["password_error"]);
        unset($_SESSION["mail_error"]);
        unset($_SESSION["photo_error"]);
    }
}