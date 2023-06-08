<?php

$id_cuenta = $_SESSION['userID']['id_cuenta'];
$oldQuestion = $_SESSION['old_question'] ?? "";
$questionRespondida = $_POST['idQuestion'] ?? "";

if (!isset($_POST['option']) && $questionRespondida == $oldQuestion) {

    $_SESSION['puntuacion'] = 0;

    $_SESSION['id_partida'] = $this->gameModel->startGame($id_cuenta);

    $dataQuestionAndUser = $this->gameModel->play();

    $_SESSION['old_question'] = isset($_SESSION['old_question']) ?? $dataQuestionAndUser['id_pregunta'];

    $dataQuestionAndUser['puntuacion'] = $_SESSION['puntuacion'];

    $dataQuestionAndUser['categoryColor'] = $this->gameModel->setCategoryColor($dataQuestionAndUser['categoria']);

    $userinfo = $this->gameModel->getUserData($id_cuenta);

    $dataQuestionAndUser['foto_perfil'] = $userinfo['foto_perfil'];

    $dataQuestionAndUser['usuario'] = $userinfo['usuario'];

} else {

    $selectedAnswer = $_POST['option'];

    $dataQuestionAndUser = $this->gameModel->play();

    $correctOpcion = $dataQuestionAndUser['es_correcta'];

    $isCorrect = $this->gameModel->verificateAnswer($selectedAnswer, $correctOpcion);

    if ($isCorrect) {

        $score = $_SESSION['puntuacion'];

        $id_partida = $_SESSION['id_partida'];

        $_SESSION['puntuacion'] = $this->gameModel->updateScore($score, $id_partida);

        $dataQuestionAndUser = $this->gameModel->play();

        $_SESSION['old_question'] = isset($_SESSION['old_question']) ?? $dataQuestionAndUser['id_pregunta'];

        $dataQuestionAndUser['puntuacion'] = $_SESSION['puntuacion'];

        $userinfo = $this->gameModel->getUserData($id_cuenta);

        $dataQuestionAndUser['foto_perfil'] = $userinfo['foto_perfil'];

        $dataQuestionAndUser['usuario'] = $userinfo['usuario'];

    } else {

        $dataQuestionAndUser = $this->gameModel->play();
        $dataQuestionAndUser['mostrarFinalPartida'] = true;
        $dataQuestionAndUser['puntuacion'] = $_SESSION['puntuacion'] ?? 0;

        $userinfo = $this->gameModel->getUserData($id_cuenta);
        $dataQuestionAndUser['foto_perfil'] = $userinfo['foto_perfil'];
        $dataQuestionAndUser['usuario'] = $userinfo['usuario'];

        unset($_POST['option']);
        unset($_SESSION['puntuacion']);
        unset($_SESSION['old_question']);

    }

}
$this->renderer->render('game', $dataQuestionAndUser ?? "");