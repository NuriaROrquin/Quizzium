<?php
session_start();
class LoginModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }


    private function validateMailOnDatabase($mail)
    {
        $validate = false;

        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {

            $sql = "SELECT *  FROM `cuenta` WHERE mail='$mail';";

            $result = $this->database->querySelect($sql);

            if (!empty($result) && $result['esta_activa'] == 1) {
                $validate = true;
            } else {
                $_SESSION['validacion'] = false;
                header("Location:/login/list");
            }
        }
        return $validate;
    }

    private function validatePassword($fields)
    {
        $validate = false;

        $mail = $fields['mail'];
        $password = md5($fields['password']);

        $sql = "SELECT *  FROM `cuenta` WHERE mail='$mail';";

        $result = $this->database->querySelect($sql);

        if ($result['contrasenia'] == $password) {
            $validate = true;
        }

        return $validate;
    }

    private function generateSession($mail)
    {
        $hash = md5(time());
        $carpeta_destino = "./public/";
        $_SESSION["user"] = $mail;
        unset($_SESSION["error"]);
        unset($_SESSION["validacion"]);
        file_put_contents($carpeta_destino."seguridad.txt", $hash);
        setcookie("seguridad", $hash, time() + 900, '/');
        header("Location: /lobby/list");
        exit();
    }


    public function validate($fields)
    {
        $fileToCompare = "./public/seguridad.txt";

        $cookie = empty($_COOKIE['seguridad']) ? false : $_COOKIE['seguridad'];

        if (file_exists($fileToCompare) && $cookie == file_get_contents($fileToCompare)) {

            header("location: /lobby/list");
            exit();

        } else {

            if ($this->validateMailOnDatabase($fields['mail'])) {

                if ($this->validatePassword($fields)) {

                    $this->generateSession($fields['mail']);

                }
                $fileToDelete = "./public/seguridad.txt";
                setcookie("seguridad", 0, time() - 1800, '/');

                if (file_exists($fileToDelete)) {
                    unlink($fileToDelete);
                }
                $_SESSION["error"] = 'contrasenia';
                header("location:/login/list");
                exit();
            }

            header("location:/login/list");
            exit();

        }

    }

    public function validateToken($token){

        $fileToCompare = "./public/seguridad.txt";

        $cookie = empty($_COOKIE['seguridad']) ? false : $_COOKIE['seguridad'];

        if (file_exists($fileToCompare) && $cookie == file_get_contents($fileToCompare)) {

            header("location: /lobby/list");
            exit();

        } else {

            $sql = "SELECT *  FROM `cuenta` WHERE token='$token';";

            $result = $this->database->querySelectAssoc($sql);

            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $formatCurrentDate = date_create()->format('Y-m-d H:i:s');

            if (!empty($result) && $result['esta_activa'] == 0) {
                $sql = "UPDATE `cuenta` SET `esta_activa`='1', `fecha_validacion` = '$formatCurrentDate' WHERE token='$token'";
                $this->database->query($sql);
                $_SESSION['validacion'] = true;
                header("Location: /login/list");
                exit();
            }

            header("Location: /login/list");
        }
    }
}



