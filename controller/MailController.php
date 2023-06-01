<?php

class MailController
{
    private $mailModel;
    private $renderer;

    public function __construct($mailModel, $renderer)
    {
        $this->mailModel = $mailModel;
        $this->renderer = $renderer;
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
            if ($this->mailModel->sendEmailAndInsertUser()) {
                header('location: /mail/sendMail&send=1');
                exit();
            }
        }
        header("location: /login/list");
    }

    public function sendMail()
    {
        //ver porque si escribo /mail/sendMail&send=1 entra a esta pagina sin hacer el register
        if( !$this->security() && $_GET['send'] == 1){
            $this->renderer->render('mail');
        }

    }

}