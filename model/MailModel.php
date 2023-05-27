<?php

require 'third-party/PHPMailer/src/PHPMailer.php';
require 'third-party/PHPMailer/src/SMTP.php';
require 'third-party/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function sendEmailAndInsertUser()
    {
        $destinatario= $_GET['mail'];

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->Username = 'quizzium.game@gmail.com';
        $mail->Password = 'lxhvgerhxckwvdke';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

        $mail->setFrom('quizzium.game@gmail.com', 'Quizzium');/*aca ponemos desde que  mail y el nombre de quien lo manda*/
        $mail->addAddress($destinatario); //aca ponemos el mail al que le vamos a mandar el correo

        $mail->isHTML(true);

        $asunto = "Hola Funciona";
        $mensaje = "Prueba Quizzium de mati";
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        if ($mail->send()) {
            header('location: /login/list');
            exit();
        } else {
            $this->deleteUser($destinatario);
            exit();
            /*aca no va a entrar nunca, a no ser que haya un problema con gmail y no se pueda mandar el mail, si el
            mail no existe aca no entra, y te "enviaria el mail" y te mandaria al login porque devuelve true el
            $mail->send()*/
        }
    }

    private function deleteUser($destinatario)
    {
        $sql = "DELETE FROM `cuenta` WHERE mail = '{$destinatario}';";
        $this->database->query($sql);
    }
}