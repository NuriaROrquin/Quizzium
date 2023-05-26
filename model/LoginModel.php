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
        $validate = false;

        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {

            $sql = "SELECT *  FROM `cuenta` WHERE mail='$mail';";

            $result = $this->database->querySelectMail($sql);

            if (!empty($result) && $result['esta_activa'] == 1) {
                $validate = true;
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

        $result = $this->database->querySelectMail($sql);

        if ($result['contrasenia'] == $password) {
            $validate = true;
        }

        return $validate;
    }


    public function validate($fields)
    {
        if ($this->validateMailOnDatabase($fields['mail'])) {

            if ($this->validatePassword($fields)) {
                header("Location: /lobby/list");
                exit();
            }
            exit("El mail o contraseña ingresada no son correctas.");
        } else {
            exit("El mail o contraseña ingresada no son correctas.");
        }
    }
}



