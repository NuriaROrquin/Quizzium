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
        $sql = "INSERT INTO `cuenta` 
                (`id_genero`,`ciudad`, `pais`,  `mail`, `usuario`, `contrasenia`, `foto_perfil`, `fecha_nacimiento`, `nombre`, `apellido`, `token`, `lat`, `lng`)
                VALUES ('{$fields['gender']}' , '{$fields['city']}' , '{$fields['country']}' , '{$fields['mail']}' , '{$fields['username']}' , '{$fields['password']}', '{$fields['photo']['url']}',
                        '{$fields['born_date']}' , '{$fields['name']}' , '{$fields['surname']}', '{$fields['token']}', '{$fields['lat']}', '{$fields['lng']}' );";

        $this->database->query($sql);
    }

    private function validateEmptyFields($fields)
    {
        $result = true;

        foreach ($fields as $field) {
            if (empty($field)) {
                $result = false;
            }
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

            $resultDataBase = $this->database->querySelectAssoc($sql);

            if ($resultDataBase['count(mail)'] > 0) {
                $result = false;
            }
        }

        return $result;
    }

    private function validateProfilePhoto($photo)
    {
        $result = false;

        $allowed_types = array('image/jpeg', 'image/png',);
        $photo_format = $photo['type'];

        if ( in_array($photo_format, $allowed_types) ) {
            $result = $this->updatePhoto($photo);
        }
        return $result;
    }

    private function updatePhoto($photo)
    {
        $temporary_file = $photo['tmp_name'];

        $file_name = uniqid() . '_' . $photo['name'];

        $destination_folder = "./public/profile-pictures/";

        if (!move_uploaded_file($temporary_file, $destination_folder . $file_name)) {
            return false;
        }
        return $file_name;
    }

    private function validateBornDate($fields){

        $result = true;

        $fechaDeNacimientoIngresada = $fields['born_date'];
        $fechaDeHoy = new DateTime();
        $fechaDeHoy = $fechaDeHoy->format('Y-m-d');

        if($fechaDeNacimientoIngresada > $fechaDeHoy){
            $result = false;
        }

        return $result;
    }

    public function validate($fields)
    {
        $errors = [];
        $fields['photo'] = $_FILES['photo'];

        if (!$this->validateEmptyFields($fields)) {
            $errors['empty_fields_error'] = true;
        }

        if (!$this->validateBornDate($fields)) {
            $errors['born_date_error'] = true;
        }


        if (!$this->validatePassword($fields['password'], $fields['verificated_password'])) {
            $errors['password_error'] = true;
        }

        if (!$this->validateMail($fields['mail'])) {
            $errors['mail_error'] = true;
        }

        $urlProfilePhoto = $this->validateProfilePhoto($fields['photo']);

        if (!$urlProfilePhoto) {
            $errors['photo_error'] = true;
        } else {
            $fields['photo']['url'] = $urlProfilePhoto;
        }

        $fields['password'] = md5($fields['password']);

        $fields['token'] = uniqid();

        if (empty($errors["empty_fields_error"]) && empty($errors["password_error"]) && empty($errors["mail_error"]) && empty($errors["photo_error"])) {
            $this->insertUser($fields);
        }

        return $errors;
    }
}



