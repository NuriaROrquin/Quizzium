<?php

class GameController
{
    private $renderer;
    private $gameModel;

    public function __construct($renderer, $gameModel)
    {
        $this->renderer = $renderer;
        $this->gameModel = $gameModel;
    }

    public function list()
    {

        $answer = $_POST['option'] ?? false;

        $id_cuenta = $_SESSION['userID']['id_cuenta'];

        $oldQuestion = $_SESSION['old_question'] ?? "";

        $questionRespondida = $_POST['idQuestion'] ?? "";

        $_SESSION['puntuacion'] = $_SESSION['puntuacion'] ?? 0;

        if( $questionRespondida != $oldQuestion || !$answer ){

            $_SESSION['puntuacion'] = 0;

            // $_SESSION['id_partida'] = $this->gameModel->startGame($id_cuenta);

            $_SESSION['id_partida'] = 1;

            $data = $this->setData($id_cuenta);

            $_SESSION['old_question'] = $data['id_question'];

        }

        else{

            $data = $this->setData($id_cuenta);

            $selectedAnswer = $answer;

            $correctOpcion = $data['es_correcta'];

            $isCorrect = $this->gameModel->verificateAnswer($selectedAnswer, $correctOpcion);

            if($isCorrect){

                $_SESSION['puntuacion'] = $_SESSION['puntuacion'] +1;

                $data['puntuacion'] = $_SESSION['puntuacion'];

                $userinfo = $this->gameModel->getUserData($id_cuenta);

                $data['foto_perfil'] = $userinfo['foto_perfil'];

                $data['usuario'] = $userinfo['usuario'];

                $_SESSION['old_question'] = $data['id_question'];

            }

            else{

                $data = $this->setData($id_cuenta);
                $data['mostrarFinalPartida'] = true;

                $userinfo = $this->gameModel->getUserData($id_cuenta);
                $data['foto_perfil'] = $userinfo['foto_perfil'];
                $data['usuario'] = $userinfo['usuario'];

                unset($_SESSION['puntuacion']);
                unset($_POST['option']);
                unset($_POST['idQuestion']);
                unset($_SESSION['old_question']);
            }
        }

        $this->renderer->render('game', $data ?? "");

    }

    private function setData($id_cuenta){

        $data = $this->gameModel->play();

        $data['puntuacion'] = $_SESSION['puntuacion'];

        $data['id_partida'] = $_SESSION['id_partida'];

        $data['categoryColor'] = $this->gameModel->setCategoryColor($data['categoria']);

        $userinfo = $this->gameModel->getUserData($id_cuenta);

        $data['foto_perfil'] = $userinfo['foto_perfil'];

        $data['usuario'] = $userinfo['usuario'];

        return $data;
    }

}