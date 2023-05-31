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
        if( isset($_POST['send']) ){
            $this->mailModel->sendEmailAndInsertUser();
        }
        header("location: /login/list");
    }

}