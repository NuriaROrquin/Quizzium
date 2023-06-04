<?php

class MailController
{
    private $mailModel;

    public function __construct($mailModel)
    {
        $this->mailModel = $mailModel;
    }

    private function security()
    {
        $userIsOn = false;
        $fileToCompare = "./config/seguridad.txt";
        $cookie = empty($_COOKIE['seguridad']) ? false : $_COOKIE['seguridad'];

        if (file_exists($fileToCompare) && $cookie == file_get_contents($fileToCompare)) {
            $userIsOn = true;
        }
        return $userIsOn;
    }

    public function list()
    {
        if (!$this->security()) {

            $destinatario = $_GET['mail'];

            if ($this->mailModel->sendEmailAndInsertUser($destinatario)) {
                $_SESSION['send_mail_to_validate'] = true;
                header('location: /login/list');
                exit();
            }
        }
        header("location: /login/list");
        exit();
    }

}