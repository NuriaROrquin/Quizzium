<?php

class MailController
{
    private $destinatario;
    private $asunto;
    private $mensaje;

    private $mailModel;

    public function __construct($destinatario, $asunto, $mensaje, $mailModel)
    {
        $this->destinatario = $destinatario;
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
        $this->mailModel = $mailModel;
    }

    public function list()
    {
        $this->mailModel->sendEmailAndInsertUser($this->destinatario, $this->asunto, $this->mensaje);
    }
}