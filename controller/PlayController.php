<?php

class PlayController
{
    private $renderer;
    private $playModel;

    public function __construct($renderer, $playModel)
    {
        $this->renderer = $renderer;
        $this->playModel = $playModel;
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

        if ( !$this->security() ) {
            header("location:/login/list");
            exit();
        }

        else{

            if( $this->playModel->play() ){
                $this->renderer->render('play', $data ?? "");
            }
        }
    }
}