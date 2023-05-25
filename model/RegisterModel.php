<?php

class RegisterModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    private function insertUser($fields)
    {
        /*$sql = "INSERT INTO `cuenta` (`id_genero`, `mail`, `ciudad`, `pais`, `usuario`, `contrasenia`,
                        `foto_perfil`, `fecha_nacimiento`, `nombre`, `apellido`) VALUES ('{$fields['gender']}' ,
                        {$fields['mail']} , {$fields['city']} , {$fields['country']} , {$fields['username']} , {$fields['password']}
                        , {$fields['photo']}  , {$fields['born_date']} , {$fields['name']} , {$fields['surname']} );";*/

        //$this->database->query($sql);
        echo "aca tengo que redireccionar al login o algo que diga que la cuenta fue creada correctamente y que falta validar el correo enviado mail";
    }

    private function validatePassword($password, $verificated_password)
    {
        $validate = false;

        if ($password == $verificated_password) {
            $validate = true;
        }

        return $validate;
    }

    private function validateMail($mail)
    {
        $validate = false;

        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $validate = true;
        }

        return $validate;
    }

    public function validate($fields)
    {

        foreach ($fields as $field) {
            if (empty($field)) {
                exit("Hay campos que faltan completar");
            }
        }

        if($this->validatePassword($fields['password'], $fields['verificated_password'])){

            if($this->validateMail($fields['mail'])){
                $this->insertUser($fields);
            }

            else {
                exit("El correo electrónico no es válido");
            }
        }

        else {
            exit("Las contraseñas no son iguales");
        }
    }
}

