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

    public function list()
    {
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

            /*
            if (isset($_SESSION['error'])) {
                $data['errorContrasenia'] = $_SESSION['error'];
                unset($_SESSION['error']);
                unset($_SESSION['validacion']);
            }

            if (isset($_SESSION['sendMail'])) {
                $data['sendMail'] = $_SESSION['sendMail'];
                unset($_SESSION['sendMail']);
            }

            if( isset($_SESSION['validacion']) ){

                if( $_SESSION['validacion'] ){
                    $data['validacionTrue'] = true;
                }
                else{
                    $data['validacionFalse'] = true;
                }
                unset($_SESSION['validacion']);
            }
            */

            $this->renderer->render('login', $data ?? "");
        }
        else{
            header("location: /lobby/list");
            exit();
        }
    }

    public function validate()
    {
        $datosIngresadosPorElUsuario = $_POST['login'];

        if($this->loginModel->validate($datosIngresadosPorElUsuario)) {
            $this->generateSession($datosIngresadosPorElUsuario);
            header("Location: /lobby/list");
            exit();
        }
        else{
            $this->deleteSession();
            $this->setErrors();
            header("Location: /login/list");
            exit();
        }
    }

    public function validateToken()
    {
        if ($this->security()) {
            header("Location: /lobby/list");
            exit();
        }

        if($this->loginModel->validateToken($_GET['token'])){
            $this->renderer->render('login', $data ?? "");
        }

        header("Location: /login/list");
        exit();
    }

    private function generateSession($fields)
    {
        $hash = md5(time());

        $carpeta_destino = "./config/";

        $_SESSION["user"] = $fields['mail'];

        $_SESSION['userID'] = $this->loginModel->searchUserIDOnDB($fields['mail']);

        file_put_contents($carpeta_destino . "seguridad.txt", $hash);
        setcookie("seguridad", $hash, time() + 1000, '/');
        return true;
    }

    private function deleteSession(){

        $fileToDelete = "./config/seguridad.txt";
        setcookie("seguridad", 0, time() - 1800, '/');

        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }
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
}