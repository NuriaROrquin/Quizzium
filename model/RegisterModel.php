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
                        `foto_perfil`, `fecha_nacimiento`, `nombre`, `apellido`) VALUES ('{$fields['gender']}' ,
                        '{$fields['mail']}' , '{$fields['city']}' , '{$fields['country']}' , '{$fields['username']}' , '{$fields['password']}'
                        , '{$fields['photo']['url']}'  , '{$fields['born_date']}' , '{$fields['name']}' ,
        '{$fields['surname']}' );
        ";

        $this->database->query($sql);

        header("Location: /login/list");

        exit();
    }

    private function validateEmptyFields($fields)
    {
        $validate = true;

        foreach ($fields as $field) {
            if (empty($field)) {
                $validate = false;
            }
            break;
        }
        return $validate;
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

            $sql = "SELECT count(mail)  FROM `cuenta` WHERE mail='$mail';";

            $result = $this->database->querySelectMail($sql);

            if ($result['count(mail)'] > 0) {
                $validate = false;
            }

        }



        return $validate;
    }

    private function validateProfilePhoto($photo)
    {
        $validate = false;

        //esto serian los tipos de imagenes permitidas (.jpg)
        $allowed_types = array('image/jpeg', 'image/png',);

        $photo_format = $photo['type'];

        //si el tipo de formato que pasamos esta en los formatos permitidos, retorna true
        if (in_array($photo_format, $allowed_types) == true) {
            $validate = $this->updatePhoto($photo);
        }
        return $validate;
    }

    private function updatePhoto($photo)
    {
        $archivo_temporal = $photo['tmp_name'];

        //uniqid crea un valor unico para la foto, si o si hay que unirlo con un "_" al nombre de la foto subida
        $nombre_archivo = uniqid() . '_' . $photo['name'];
        $carpeta_destino = "./public/profile-pictures/";

        if (!move_uploaded_file($archivo_temporal, $carpeta_destino . $nombre_archivo)) {
            return false;
        }
        return $nombre_archivo;
    }

    public function validate($fields)
    {
        $fields['photo'] = $_FILES['photo'];

        if (!$this->validateEmptyFields($fields)) {
            exit("Hay campos que faltan completar");
        }

        if (!$this->validatePassword($fields['password'], $fields['verificated_password'])) {
            exit("Las contraseñas ingresadas son distintas.");
        }

        if (!$this->validateMail($fields['mail'])) {
            exit("El correo electrónico no es válido o ya esta en uso.");
        }

        $urlProfilePhoto = $this->validateProfilePhoto($fields['photo']);

        if (!$urlProfilePhoto) {
            exit("La foto ingresada debe ser de formato .png o .jpg");
        } else {
            $fields['photo']['url'] = $urlProfilePhoto;
        }

        $this->insertUser($fields);
    }
}



