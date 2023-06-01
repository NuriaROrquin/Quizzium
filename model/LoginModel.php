<?php

class LoginModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }


    private function validateMailOnDatabase($mail)
    {
        $result = false;

        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {

            $sql = "SELECT *  FROM `cuenta` WHERE mail='$mail';";

            $resultDataBase = $this->database->querySelect($sql);

            if (!empty($resultDataBase) && $resultDataBase['esta_activa'] == 1) {
                $result = true;
            }
            else{
                $_SESSION['validacion'] = false;
            }
        }

        return $result;
    }

    private function validatePassword($fields)
    {
        $result = false;

        $mail = $fields['mail'];
        $password = md5($fields['password']);

        $sql = "SELECT *  FROM `cuenta` WHERE mail='$mail';";

        $resultDataBase = $this->database->querySelect($sql);

        if ($resultDataBase['contrasenia'] == $password) {
            $result = true;
        }

        return $result;
    }

    private function generateSession($mail)
    {
        $hash = md5(time());
        $carpeta_destino = "./config/";
        $_SESSION["user"] = $mail;
        file_put_contents($carpeta_destino . "seguridad.txt", $hash);
        setcookie("seguridad", $hash, time() + 1000, '/');
        return true;
    }


    public function validate($fields)
    {
        $result = false;

        if ($this->validateMailOnDatabase($fields['mail'])) {

            if ($this->validatePassword($fields)) {

                $result = $this->generateSession($fields['mail']);

            } else {

                $fileToDelete = "./config/seguridad.txt";
                setcookie("seguridad", 0, time() - 1800, '/');

                if (file_exists($fileToDelete)) {
                    unlink($fileToDelete);
                }

                $_SESSION["error"] = true;
            }
        }
        return $result;
    }

    public function validateToken($token)
    {
        $result = false;

        $sql = "SELECT *  FROM `cuenta` WHERE token='$token';";

        $resultDatabase = $this->database->querySelectAssoc($sql);

        if (!empty($resultDatabase) && $resultDatabase['esta_activa'] == 0) {

            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $formatCurrentDate = date_create()->format('Y-m-d H:i:s');

            $sql = "UPDATE `cuenta` SET `esta_activa`='1', `fecha_validacion` = '$formatCurrentDate' WHERE token='$token'";
            $this->database->query($sql);

            $_SESSION['validacion'] = true;
            $result = true;
        }
        return $result;
    }
}
/*
        $fileToCompare = "./config/seguridad.txt";

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
*/




