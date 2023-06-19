<?php

include_once('helpers/RolEnum.php');

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
        $userId = $_SESSION['userID']['id_cuenta'];
        $user['rol'] = $this->profileModel->getRol($userId);

        switch ($user['rol']) {
            case RolEnum::ADMINISTRADOR:
                $data['administrador'] = true;
                $data['editor'] = true;
                $this->renderer->render('factory', $data);
                break;
            case RolEnum::EDITOR:
                $data['editor'] = true;
                $data['questions'] = $this->factoryModel->getPendingQuestions();
                $this->renderer->render('factory', $data);
                break;
            default:
                $this->renderer->render('factory');
                break;
        }

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
        $id_cuenta = $_SESSION['userID']['id_cuenta'];

        $errors = $this->factoryModel->validate($question);

        if (!empty($errors)) {
            $data = json_encode($errors, JSON_UNESCAPED_UNICODE);
        } else {
            $result = $this->factoryModel->sendQuestion($question, $id_cuenta);
            $data = json_encode($result, JSON_UNESCAPED_UNICODE);
        }

        echo $data;
    }

    public function getInfoPendingQuestion()
    {

        $id = $_GET['id'] ?? "";

        $results = $this->factoryModel->getInfoPendingQuestions($id);

        $data = json_encode($results, JSON_UNESCAPED_UNICODE);

        echo $data;
    }

    public function acceptQuestion()
    {
        $question['id'] = $_POST['id'] ?? "";
        $question['action'] = $_POST['action'] ?? "";
        $question['createdDate'] = $_POST['createdDate'] ?? "";
        $question['category'] = $_POST['category'] ?? "";
        $question['title'] = $_POST['title'] ?? "";
        $question['answerOne'] = $_POST['answerOne'] ?? "";
        $question['answerTwo'] = $_POST['answerTwo'] ?? "";
        $question['answerThree'] = $_POST['answerThree'] ?? "";
        $question['answerFour'] = $_POST['answerFour'] ?? "";
        $question['correctAnswer'] = $_POST['correctAnswer'] ?? "";
        $question['idAnswerOne'] = $_POST['idAnswerOne'] ?? "";
        $question['idAnswerTwo'] = $_POST['idAnswerTwo'] ?? "";
        $question['idAnswerThree'] = $_POST['idAnswerThree'] ?? "";
        $question['idAnswerFour'] = $_POST['idAnswerFour'] ?? "";

        $errors = $this->factoryModel->validate($question);

        if (!empty($errors)) {
            $data = json_encode($errors, JSON_UNESCAPED_UNICODE);
        } else {
            $result = $this->factoryModel->updateQuestion($question);
            $data = json_encode($result, JSON_UNESCAPED_UNICODE);
        }

        echo $data;
    }

    public function denyQuestion()
    {
        $question['id'] = $_REQUEST['id'] ?? "";
        $result = $this->factoryModel->deleteQuestion($question);
        $data = json_encode($result, JSON_UNESCAPED_UNICODE);
        echo $data;
    }

}

















