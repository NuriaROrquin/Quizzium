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
        if( !$this->security() ){
            $this->mailModel->sendEmailAndInsertUser();
        }
        header("location: /login/list");
    }

}