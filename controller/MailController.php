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
        $this->mailModel->sendEmailAndInsertUser();
    }
}