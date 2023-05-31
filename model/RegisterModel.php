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
        $sql = "INSERT INTO `cuenta` (`id_genero`, `mail`, `ciudad`, `pais`, `usuario`, `contrasenia`,
                        `foto_perfil`, `fecha_nacimiento`, `nombre`, `apellido`, `token`) VALUES ('{$fields['gender']}' ,
                        '{$fields['mail']}' , '{$fields['city']}' , '{$fields['country']}' , '{$fields['username']}' , '{$fields['password']}'
                        , '{$fields['photo']['url']}'  , '{$fields['born_date']}' , '{$fields['name']}' ,
        '{$fields['surname']}', '{$fields['token']}' );
        ";

        return $this->database->query($sql);
    }

    private function validateEmptyFields($fields)
    {
        $result = true;

        foreach ($fields as $field) {
            if (empty($field)) {
                $result = false;
            }
            break;
        }
        return $result;
    }

    private function validatePassword($password, $verificated_password)
    {
        $result = false;

        if ($password == $verificated_password) {
            $result = true;
        }
        return $result;
    }

    private function validateMail($mail)
    {
        $result = false;

        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {

            $result = true;

            $sql = "SELECT count(mail)  FROM `cuenta` WHERE mail='$mail';";

            $resultDataBase = $this->database->querySelect($sql);

            if ($resultDataBase['count(mail)'] > 0) {
                $result = false;
            }
        }

        return $result;
    }

    private function validateProfilePhoto($photo)
    {
        $result = false;

        //esto serian los tipos de imagenes permitidas (.jpg)
        $allowed_types = array('image/jpeg', 'image/png',);

        $photo_format = $photo['type'];

        //si el tipo de formato que pasamos esta en los formatos permitidos, retorna true
        if (in_array($photo_format, $allowed_types) == true) {
            $result = $this->updatePhoto($photo);
        }

        return $result;
    }

    private function updatePhoto($photo)
    {
        $temporary_file = $photo['tmp_name'];

        //uniqid crea un valor unico para la foto, si o si hay que unirlo con un "_" al nombre de la foto subida
        $file_name = uniqid() . '_' . $photo['name'];
        $destination_folder = "./public/profile-pictures/";

        if (!move_uploaded_file($temporary_file, $destination_folder . $file_name)) {
            return false;
        }
        return $file_name;
    }

    public function validate($fields)
    {

        $fields['photo'] = $_FILES['photo'];


        if ( !$this->validateEmptyFields($fields) ) {
            $_SESSION["empty_fields_error"] = true;
        }

        if ( !$this->validatePassword($fields['password'], $fields['verificated_password']) ) {
            $_SESSION["password_error"] = true;
        }

        if ( !$this->validateMail($fields['mail']) ) {
            $_SESSION["mail_error"] = true;
        }

        $urlProfilePhoto = $this->validateProfilePhoto($fields['photo']);

        if ( !$urlProfilePhoto ) {
            $_SESSION["photo_error"] = true;
        } else {
            $fields['photo']['url'] = $urlProfilePhoto;
        }

        $fields['password'] = md5($fields['password']);

        $fields['token'] = uniqid();

        if( empty($_SESSION["empty_fields_error"]) && empty($_SESSION["password_error"]) && empty($_SESSION["mail_error"]) && empty($_SESSION["photo_error"])){
            $result = $this->insertUser($fields);
        }
        else{
            $result = false;
        }

        return $result;

    }
}



