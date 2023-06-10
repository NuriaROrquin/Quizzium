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
        $selectedAnswer = $_POST['option'] ?? false;
        $questionRespondida = $_POST['idQuestion'] ?? "";
        $oldQuestion = $_SESSION['old_question'] ?? "";
        $id_cuenta = $_SESSION['userID']['id_cuenta'];
        $_SESSION['puntuacion'] = $_SESSION['puntuacion'] ?? 0;

        $data = $this->setData($id_cuenta);

        $correctOpcion = $data['es_correcta'];

        if( $questionRespondida != $oldQuestion || !$selectedAnswer ){

            $_SESSION['puntuacion'] = 0;

            $data['puntuacion'] = 0;

            // $_SESSION['id_partida'] = $this->gameModel->startGame($id_cuenta);

            $_SESSION['id_partida'] = 1;

            $_SESSION['old_question'] = $data['id_question'];

        }

        else{

            $isCorrect = $this->gameModel->verificateAnswer($selectedAnswer, $correctOpcion);

            if($isCorrect){

                $_SESSION['puntuacion'] = $_SESSION['puntuacion'] +1;

                $data['puntuacion'] = $_SESSION['puntuacion'];

                $_SESSION['old_question'] = $data['id_question'];

                unset($_POST['idQuestion']);
            }

            else{

                $data =  $_SESSION['oldData'];

                $data['mostrarFinalPartida'] = true;

                unset($_SESSION['puntuacion']);
                unset($_POST['option']);
                unset($_POST['idQuestion']);
                unset($_SESSION['old_question']);
                unset($_SESSION['oldData']);
            }
        }

        $this->renderer->render('game', $data ?? "");

        $_SESSION['oldData'] = $data;

    }

    private function setData($id_cuenta){

        $data = $this->gameModel->getQuestion();

        $data['puntuacion'] = $_SESSION['puntuacion'];

        $data['id_partida'] = $_SESSION['id_partida'] ?? null;

        $data['categoryColor'] = $this->gameModel->setCategoryColor($data['categoria']);

        $userinfo = $this->gameModel->getUserData($id_cuenta);

        $data['foto_perfil'] = $userinfo['foto_perfil'];

        $data['usuario'] = $userinfo['usuario'];

        return $data;
    }

    public function nuevaPregunta (){
        $id_cuenta = $_SESSION['userID']['id_cuenta'];
        return $data = $this->setData($id_cuenta);
    }


}