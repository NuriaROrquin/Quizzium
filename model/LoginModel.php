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

            $resultDataBase = $this->database->querySelectAssoc($sql);

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

        $resultDataBase = $this->database->querySelectAssoc($sql);

        if ($resultDataBase['contrasenia'] == $password) {
            $result = true;

        }

        return $result;
    }

    public function searchUserIDOnDB($mail){
        $sql = "SELECT id_cuenta  FROM `cuenta` WHERE mail='$mail';";
        return $this->database->querySelectAssoc($sql);
    }

    public function validate($fields)
    {
        $result = false;

        if ($this->validateMailOnDatabase($fields['mail'])) {

            if ($this->validatePassword($fields)) {
                $result = true;
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




