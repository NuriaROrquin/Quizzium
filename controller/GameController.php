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

            $_SESSION['id_juego'] = $this->gameModel->startGame($id_cuenta);

            $_SESSION['old_question'] = $data['id_question'];

        }

        else{

            $isCorrect = $this->gameModel->verificateAnswer($selectedAnswer, $correctOpcion);

            $this->gameModel->insertAnswer($isCorrect, $id_cuenta, $oldQuestion);

            if($isCorrect){

                $puntuacion = $this->gameModel->updateScore($_SESSION['id_juego']);

                var_dump($puntuacion);

                $data['puntuacion'] = $puntuacion;

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

        $data = $this->gameModel->getQuestion($id_cuenta);

        $data['id_juego'] = $_SESSION['id_juego'] ?? null;

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