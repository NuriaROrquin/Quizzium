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
        unset($_SESSION['puntuacion']);
        $selectedAnswer = $_POST['option'] ?? false;
        $id_cuenta = $_SESSION['userID']['id_cuenta'];
        $newQuestion = $_POST['idQuestion'] ?? "";
        $oldQuestion = $_SESSION['old_question'] ?? "";

        $data = $this->setData($id_cuenta);

        $userinfo = $this->gameModel->getUserData($id_cuenta);

        $data['foto_perfil'] = $userinfo['foto_perfil'];

        $data['usuario'] = $userinfo['usuario'];

        $_SESSION['respuestaCorrecta'] = $data['textoOpcionCorrecta'];

        $data['puntuacion'] = 0;

        if ($newQuestion == $oldQuestion || !$selectedAnswer) {

            $_SESSION['id_juego'] = $this->gameModel->startGame($id_cuenta);

            $_SESSION['old_question'] = $data['id_question'];

        }

        $this->renderer->render('game', $data ?? "");

        $_SESSION['timestamp'] = time();
    }

    private function setData($id_cuenta)
    {
        $data = $this->gameModel->getQuestion($id_cuenta);

        $data['id_juego'] = $_SESSION['id_juego'] ?? null;

        $data['categoryColor'] = $this->gameModel->setCategoryColor($data['categoria']);

        $data['categoryName'] = $this->gameModel->getCategoryName($data['categoria']);

        return $data;
    }

    public function answer()
    {
        $cronometroEnCero = $_POST['cronometroEnCero'] ?? false;
        $selectedAnswer = $_POST['selectedOption'] ?? false;
        $id_pregunta = $_POST['id_question'] ?? false;
        $id_cuenta = $_SESSION['userID']['id_cuenta'];
        $oldQuestion = $_SESSION['old_question'] ?? "";

        $timestampActual = time();
        $timestampGuardado = $_SESSION['timestamp'] ?? $timestampActual;
        $diferencia = $timestampActual - $timestampGuardado;

        if ($diferencia >= 20 || $cronometroEnCero == 1) {

            $data['textoOpcionCorrecta'] = $_SESSION['respuestaCorrecta'];
            $data['puntuacion'] = $_SESSION['puntuacion'] ?? 0;
            $data['mostrarFinalPartida'] = true;

            $this->unsetVariables();

            $isCorrect = $this->gameModel->verificateAnswer($id_pregunta, $selectedAnswer);
            $this->gameModel->insertAnswer($isCorrect, $id_cuenta, $oldQuestion);

        } else {

                $isCorrect = $this->gameModel->verificateAnswer($id_pregunta, $selectedAnswer);
                $this->gameModel->insertAnswer($isCorrect, $id_cuenta, $oldQuestion);

                if ($isCorrect) {

                    $data = $this->setData($id_cuenta);

                    $data['puntuacion'] = $this->gameModel->updateScore($_SESSION['id_juego']);

                    $_SESSION['puntuacion'] = $data['puntuacion'];
                    $_SESSION['old_question'] = $data['id_question'];
                    $_SESSION['respuestaCorrecta'] = $data['textoOpcionCorrecta'];
                    $_SESSION['timestamp'] = time();

                } else {
                    $data['textoOpcionCorrecta'] = $_SESSION['respuestaCorrecta'];
                    $data['puntuacion'] = $_SESSION['puntuacion'] ?? 0;
                    $data['mostrarFinalPartida'] = true;
                    $this->unsetVariables();
                }
            }

        $data = json_encode($data, JSON_UNESCAPED_UNICODE);

        echo $data;

    }

    private function unsetVariables()
    {
        unset($_POST['option']);
        unset($_POST['idQuestion']);
        unset($_SESSION['old_question']);
        unset($_SESSION['puntuacion']);
        unset($_SESSION['timestamp']);
        unset($_SESSION['id_juego']);
    }

    public function reportarPregunta(){
        $data = $_POST;
        $userID = $_SESSION["userID"]["id_cuenta"];

        if($this->gameModel->addQuestionReported($data,$userID)){
            header("location:/lobby/list");
        }
    }
}