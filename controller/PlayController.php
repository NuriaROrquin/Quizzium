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
            unset($_POST['option']);
            if ($answer) {
                $isCorrect = $this->verificateAnswer($answer);

                if ($isCorrect) {
                    $question = $this->playModel->play();
                    $question['puntuacion'] = $_SESSION['puntuacion'];
                    $question['categoryColor'] = $this->playModel->showCategory($question['categoria']);
                    $id_cuenta = $_SESSION['userID']['id_cuenta'];
                    $userinfo = $this->playModel->getUserData($id_cuenta);
                    $question['foto_perfil'] = $userinfo['foto_perfil'];
                    $question['usuario'] = $userinfo['usuario'];
                    $this->renderer->render('play', $question ?? "");
                }
                else {
                    $puntuacion = $_SESSION['puntuacion'];
                    unset($_SESSION['puntuacion']);
                    var_dump($puntuacion);
                }
            }
            else {
                unset($_SESSION['puntuacion']);
                $question = $this->playModel->play();
                $question['puntuacion'] = 0;
                $question['categoryColor'] = $this->playModel->showCategory($question['categoria']);
                $id_cuenta = $_SESSION['userID']['id_cuenta'];
                $userinfo = $this->playModel->getUserData($id_cuenta);
                $question['foto_perfil'] = $userinfo['foto_perfil'];
                $question['usuario'] = $userinfo['usuario'];
                $this->renderer->render('play', $question ?? "");
            }
        }
    }

    private function verificateAnswer($answer)
    {

        if (!isset($_SESSION['puntuacion'])) {
            $_SESSION['puntuacion'] = 0;
        }

        $isCorrect = false;
        if ($answer == $_SESSION['opcionCorrecta']) {
            unset($_SESSION['opcionCorrecta']);
            $isCorrect = true;

            $_SESSION['puntuacion'] = $_SESSION['puntuacion'] + 1;

        }
        return $isCorrect;
    }

    private function showScore()
    {


        return $isCorrect;
    }

}