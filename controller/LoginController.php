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

    public function list()
    {
        $alerts = [];

        if (isset($_SESSION['mail_not_validated'])) {
            $alerts['mail_not_validated'] = true;
        }

        if (isset($_SESSION['incorrect_data'])) {
            $alerts['incorrect_data'] = true;
        }

        if (isset($_SESSION['send_mail_to_validate'])) {
            $alerts['send_mail_to_validate'] = true;
        }

        if (isset($_SESSION['validated_account'])) {
            $alerts['validated_account'] = true;
        }

        $this->renderer->render('login', $alerts ?? "");
        $this->unsetAlertSessions();
    }

    public function validate()
    {
        $datosIngresadosPorElUsuario = $_POST['login'];

        if( !empty($datosIngresadosPorElUsuario) ){
            $result = $this->loginModel->validate($datosIngresadosPorElUsuario);

            if (empty($result)) {
                $this->generateSession($datosIngresadosPorElUsuario);
                header("Location: /lobby/list");
            }
            else {
                $this->deleteSession();
                $this->setAlerts($result);
                header("Location: /login/list");
            }
            exit();
        }
        header("Location: /login/list");
        exit();

    }

    public function validateToken()
    {

        if ($this->loginModel->validateToken($_GET['token'])) {

            $_SESSION['validated_account'] = true;
            $this->renderer->render('login', $data ?? "");
        }

        header("Location: /login/list");
        exit();
    }


    //***********************************  FUNCIONES PRIVADAS  ***********************************

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

    private function unsetAlertSessions()
    {
        unset($_SESSION['mail_not_validated']);
        unset($_SESSION["incorrect_data"]);
        unset($_SESSION['send_mail_to_validate']);
        unset($_SESSION["validated_account"]);
    }

    private function deleteSession()
    {
        $fileToDelete = "./config/seguridad.txt";
        setcookie("seguridad", 0, time() - 1800, '/');

        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }
    }

    private function setAlerts($alerts)
    {
        if (isset($alerts['mail_not_validated'])) {
            $_SESSION['mail_not_validated'] = $alerts['mail_not_validated'];
        } else {
            unset($_SESSION['mail_not_validated']);
        }

        if (isset($alerts['incorrect_data'])) {
            $_SESSION['incorrect_data'] = $alerts['incorrect_data'];
        } else {
            unset($_SESSION['incorrect_data']);
        }

        if (isset($alerts['send_mail_to_validate'])) {
            $_SESSION['send_mail_to_validate'] = $alerts['send_mail_to_validate'];
        } else {
            unset($_SESSION['send_mail_to_validate']);
        }

        if (isset($alerts['validated_account'])) {
            $_SESSION['validated_account'] = $alerts['validated_account'];
        } else {
            unset($_SESSION['validated_account']);
        }
    }

}