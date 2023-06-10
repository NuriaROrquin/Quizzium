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
        $newQuestion = $_POST['idQuestion'] ?? "";
        $oldQuestion = $_SESSION['old_question'] ?? "";
        $id_cuenta = $_SESSION['userID']['id_cuenta'];

        $data = $this->setData($id_cuenta);

        $correctOpcion = $_SESSION['oldData']['es_correcta'] ?? false;

        if( $newQuestion != $oldQuestion || !$selectedAnswer ){

            $data['puntuacion'] = 0;

            $_SESSION['id_juego'] = $this->gameModel->startGame($id_cuenta);

            $_SESSION['old_question'] = $data['id_question'];

        }

        else{

            $isCorrect = $this->gameModel->verificateAnswer($selectedAnswer, $correctOpcion);

            $this->gameModel->insertAnswer($isCorrect, $id_cuenta, $oldQuestion);

            if($isCorrect){

                $puntuacion = $this->gameModel->updateScore($_SESSION['id_juego']);

                $data['puntuacion'] = $puntuacion;

                $_SESSION['old_question'] = $data['id_question'];

                unset($_POST['idQuestion']);
            }

            else{

                $data =  $_SESSION['oldData'];

                //var_dump($data);
                //exit();

                $data['mostrarFinalPartida'] = true;

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

        $data['categoryName'] = $this->gameModel->getCategoryName($data['categoria']);

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