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

        if (!$this->security()) {

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
            unset($errors);
            $this->unsetErrorsSessions();

        } else {
            header("location: /lobby/list");
            exit();
        }
    }

    public function validate()
    {
        $this->unsetErrorsSessions();

        if (!$this->security() && isset($_POST['send'])) {

            $datosIngresadosPorElUsuario = $_POST['register'];

            $result = $this->registerModel->validate($datosIngresadosPorElUsuario);

            if (empty($result)) {
                header('location: /mail/list&mail=' . urlencode($_POST['register']['mail']));
                exit();
            } else {
                $this->setErrores($result);
            }
        }
        header('location: /register/list');
        exit();
    }

    private function setErrores($errores)
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

    private function security()
    {
        $userIsOn = false;
        $fileToCompare = "./config/seguridad.txt";
        $cookie = empty($_COOKIE['seguridad']) ? false : $_COOKIE['seguridad'];

        if (file_exists($fileToCompare) && $cookie == file_get_contents($fileToCompare)) {
            $userIsOn = true;
        }
        return $userIsOn;
    }
}