<?php

class MailController
{
    private $mailModel;

    public function __construct($mailModel)
    {
        $this->mailModel = $mailModel;
    }

    public function list()
    {

        $destinatario = $_GET['mail'];

        if ($this->mailModel->sendEmailAndInsertUser($destinatario)) {
            $_SESSION['send_mail_to_validate'] = true;
        }

        header("location: /login/list");
        exit();
    }

}