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

            echo "answer";
            var_dump($answer);

            $currentQuestion = $_POST['idQuestion'] ?? "";

            echo "currentQuestionGame";
            var_dump($currentQuestion);

            $oldQuestion = $_SESSION['old_question'] ?? "";

            echo "oldQuestionGame";
            var_dump($oldQuestion);

            if ( $answer && $currentQuestion == $oldQuestion ) {

                $isCorrect = $this->verificateAnswer( $answer );

                if ($isCorrect) {

                    $this->addScore();

                    unset($_POST['option']);

                    $question = $this->playModel->play();

                    $_SESSION['old_question'] = $question['id'];

                    $question['puntuacion'] = $_SESSION['puntuacion'];

                    $question['categoryColor'] = $this->playModel->showCategory($question['categoria']);

                    $id_cuenta = $_SESSION['userID']['id_cuenta'];

                    $userinfo = $this->playModel->getUserData($id_cuenta);

                    $question['foto_perfil'] = $userinfo['foto_perfil'];
                    $question['usuario'] = $userinfo['usuario'];

                    $this->renderer->render('play', $question ?? "");
                } else {
                    $puntuacion = $_SESSION['puntuacion'];
                    unset($_SESSION['puntuacion']);
                    echo "Su puntuacion fue de:" . $puntuacion;
                }

            }

            else {

                unset($_SESSION['old_question']);

                $id_cuenta = $_SESSION['userID']['id_cuenta'];

                //$_SESSION['id_partida'] = $this->playModel->startGame($id_cuenta);
                unset($_POST['option']);
                unset($_SESSION['puntuacion']);

                $question = $this->playModel->play();

                $_SESSION['old_question'] = $question['id'];

                $question['puntuacion'] = 0;

                $question['categoryColor'] = $this->playModel->showCategory($question['categoria']);

                $userinfo = $this->playModel->getUserData($id_cuenta);

                $question['foto_perfil'] = $userinfo['foto_perfil'];
                $question['usuario'] = $userinfo['usuario'];

                echo "oldQuestionELSE";
                var_dump($_SESSION['old_question']);

                $this->renderer->render('play', $question ?? "");
            }
        }


    }

    private
    function verificateAnswer($answer)
    {

        if (!isset($_SESSION['puntuacion'])) {
            $_SESSION['puntuacion'] = 0;
        }

        $isCorrect = false;
        if ($answer == $_SESSION['opcionCorrecta']) {
            unset($_SESSION['opcionCorrecta']);
            $isCorrect = true;
        }
        return $isCorrect;
    }

    private function addScore(){

        $_SESSION['puntuacion'] = $_SESSION['puntuacion'] + 1;
    }

}