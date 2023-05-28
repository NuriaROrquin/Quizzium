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
                exit("Tenes que validar tu cuenta");
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


    public function validate($fields)
    {
        if ($this->validateMailOnDatabase($fields['mail'])) {

            if ($this->validatePassword($fields)) {

                $hash = md5(time());
                $carpeta_destino = "./public/";
                $_SESSION["user"] = $fields['mail'];
                unset($_SESSION["error"]);
                file_put_contents($carpeta_destino."seguridad.txt", $hash);
                setcookie("seguridad", $hash, time() + 900, '/');
                header("Location: /lobby/list");
                exit();
            }

            $fileToDelete = "./public/seguridad.txt";
            $_SESSION["error"] = "password";
            setcookie("seguridad", 0, time() - 1800, '/');

            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
            }
            $_SESSION["error"] = 'contrasenia';
            header("location:/login/list");
            exit();
        }
    }

    public function validateToken($token){
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
    }
}



