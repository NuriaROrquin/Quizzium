<?php

include_once('helpers/RolEnum.php');

class ReportController
{
    private $renderer;
    private $reportModel;
    private $profileModel;

    public function __construct($renderer, $reportModel, $profileModel)
    {
        $this->renderer = $renderer;
        $this->reportModel = $reportModel;
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
                $this->renderer->render('report', $data);
                break;
            case RolEnum::EDITOR:
                $data['editor'] = true;
                $data['questions'] = $this->reportModel->getPendingQuestions();
                $this->renderer->render('report', $data);
                break;
            default:
                $this->renderer->render('report');
                break;
        }

    }

    public function getInfoPendingQuestion()
    {

        $id = $_GET['id'] ?? "";

        $results = $this->reportModel->getInfoPendingQuestions($id);

        $data = json_encode($results, JSON_UNESCAPED_UNICODE);

        echo $data;
    }

    public function updateQuestion()
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

        $errors = $this->reportModel->validate($question);

        if (!empty($errors)) {
            $data = json_encode($errors, JSON_UNESCAPED_UNICODE);
        } else {
            $result = $this->reportModel->updateQuestion($question);
            $data = json_encode($result, JSON_UNESCAPED_UNICODE);
        }

        echo $data;
    }

    public function deleteQuestion()
    {
        $question['id'] = $_REQUEST['id'] ?? "";
        $result = $this->reportModel->deleteQuestion($question);
        $data = json_encode($result, JSON_UNESCAPED_UNICODE);
        echo $data;
    }

    public function ignoreReport()
    {
        $question['id_report'] = $_REQUEST['id_report'] ?? "";
        $question['id_question'] = $_REQUEST['id_question'] ?? "";
        $result = $this->reportModel->ignoreReport($question);
        $data = json_encode($result, JSON_UNESCAPED_UNICODE);
        echo $data;
    }
}