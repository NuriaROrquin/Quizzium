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
}