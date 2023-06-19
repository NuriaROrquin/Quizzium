<?php

class FactoryController
{
    private $renderer;
    private $factoryModel;
    private $profileModel;

    public function __construct($renderer, $factoryModel, $profileModel)
    {
        $this->renderer = $renderer;
        $this->factoryModel = $factoryModel;
        $this->profileModel = $profileModel;
    }

    public function list()
    {
        $this->renderer->render('factory');
    }

    public function sendQuestion()
    {
        $question['category'] = $_POST['category'] ?? "";
        $question['title'] = $_POST['title'] ?? "";
        $question['answerOne'] = $_POST['answerOne'] ?? "";
        $question['answerTwo'] = $_POST['answerTwo'] ?? "";
        $question['answerThree'] = $_POST['answerThree'] ?? "";
        $question['answerFour'] = $_POST['answerFour'] ?? "";
        $question['correctAnswer'] = $_POST['correctAnswer'] ?? "";

        $errors = $this->factoryModel->validate($question);

        if (!empty($errors)) {
            $data = json_encode($errors, JSON_UNESCAPED_UNICODE);
        }else{
            $result = $this->factoryModel->sendQuestion($question);
            $data = json_encode($result, JSON_UNESCAPED_UNICODE);
        }

        echo $data;
    }

    public function getQuestions(){
        $userId = $_SESSION['userID']['id_cuenta'];
        $rol['editor'] = $this->profileModel->getRol($userId);

        $this->renderer->render('factory', $rol);
    }

}

















