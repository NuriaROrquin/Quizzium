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

    public function game(){

        if (!$this->security()) {
            header("location:/login/list");
            exit();
        }

        $id_cuenta = $_SESSION['userID']['id_cuenta'];

        $oldQuestion = $_SESSION['old_question'] ?? "";
        $questionRespondida = $_POST['idQuestion'] ?? "";

        if( !isset($_POST['option']) && $questionRespondida == $oldQuestion){

            $_SESSION['puntuacion'] = 0;

            $this->playModel->startGame($id_cuenta);

            $dataQuestionAndUser = $this->playModel->play();

            $_SESSION['old_question'] = isset($_SESSION['old_question']) ?? $dataQuestionAndUser['id_pregunta'];

            $dataQuestionAndUser['puntuacion'] = $_SESSION['puntuacion'];

            $dataQuestionAndUser['categoryColor'] = $this->playModel->showCategory($dataQuestionAndUser['categoria']);

            $userinfo = $this->playModel->getUserData($id_cuenta);

            $dataQuestionAndUser['foto_perfil'] = $userinfo['foto_perfil'];

            $dataQuestionAndUser['usuario'] = $userinfo['usuario'];

            $this->renderer->render('play', $dataQuestionAndUser ?? "");
        }
        else {

            $selectedAnswer = $_POST['option'];

            $dataQuestionAndUser = $this->playModel->play();

            $correctOpcion = $dataQuestionAndUser['es_correcta'];

            $isCorrect = $this->playModel->verificateAnswer($selectedAnswer, $correctOpcion);

            if( $isCorrect ){

                $score = $_SESSION['puntuacion'];

                $_SESSION['puntuacion'] = $this->playModel->addScore($score);

                $dataQuestionAndUser = $this->playModel->play();

                $_SESSION['old_question'] = isset($_SESSION['old_question']) ?? $dataQuestionAndUser['id_pregunta'];

                $dataQuestionAndUser['puntuacion'] = $_SESSION['puntuacion'];

                $userinfo = $this->playModel->getUserData($id_cuenta);

                $dataQuestionAndUser['foto_perfil'] = $userinfo['foto_perfil'];

                $dataQuestionAndUser['usuario'] = $userinfo['usuario'];

            }

            else{

                $dataQuestionAndUser = $this->playModel->play();
                $dataQuestionAndUser['mostrarFinalPartida'] = true;
                $dataQuestionAndUser['puntuacion'] = $_SESSION['puntuacion'] ?? 0;

                $userinfo = $this->playModel->getUserData($id_cuenta);
                $dataQuestionAndUser['foto_perfil'] = $userinfo['foto_perfil'];
                $dataQuestionAndUser['usuario'] = $userinfo['usuario'];

                unset($_POST['option']);
                unset($_SESSION['puntuacion']);
                unset($_SESSION['old_question']);

            }
            
            $this->renderer->render('play', $dataQuestionAndUser ?? "");
        }
    }

    /*
    public function game()
    {

        if (!$this->security()) {
            header("location:/login/list");
            exit();
        } else {

            $answer = $_POST['option'] ?? false;

            $questionRespondida = $_POST['idQuestion'] ?? "";

            if ($answer && $questionRespondida == $_SESSION['old_question']) {

                $isCorrect = $this->verificateAnswer($answer);

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
                }

                else {
                    $data['mostrarFinalPartida'] = true;
                    $data['puntuacionFinal'] = $_SESSION['puntuacion'];
                    $data['opcionCorrecta'] = $_SESSION['textoOpcionCorrecta'];
                    $data['puntuacion'] = $_SESSION['puntuacion'];

                    $id_cuenta = $_SESSION['userID']['id_cuenta'];

                    $userinfo = $this->playModel->getUserData($id_cuenta);

                    $data['foto_perfil'] = $userinfo['foto_perfil'];
                    $data['usuario'] = $userinfo['usuario'];

                    unset($_SESSION['puntuacion']);
                    unset($_SESSION['old_question']);

                    $this->renderer->render('play', $data ?? "");
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
            unset($_SESSION['textoOpcionCorrecta']);
            $isCorrect = true;
        }
        return $isCorrect;
    }

    private function addScore()
    {
        $_SESSION['puntuacion'] = $_SESSION['puntuacion'] + 1;
    }
    */

}