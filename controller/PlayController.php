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

    public function game()
    {

        if (!$this->security()) {
            header("location:/login/list");
            exit();
        } else {
            $answer = $_POST['option'] ?? false;

            var_dump($_POST['option']);


            if($answer){
                $isCorrect = $this->playModel->verificateAnswer($answer);


                if ($isCorrect) {
                    $question = $this->playModel->play();

                    $this->renderer->render('play', $question ?? "");
                } else {
                    $puntuacion = $_SESSION['puntuacion'];
                    unset($_SESSION['puntuacion']);

                }
            }else{
                $question = $this->playModel->play();
                $this->renderer->render('play', $question ?? "");
            }
        }
    }
}