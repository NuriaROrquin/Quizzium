<?php

class AdminController
{

    private $renderer;
    private $statisticsModel;

    public function __construct($renderer, $statisticsModel)
    {
        $this->renderer = $renderer;
        $this->statisticsModel = $statisticsModel;
    }

    public function list()
    {
        $this->renderer->render("admin");
    }

    public function getPlayersByCountry()
    {
        $filters['dateFrom'] = $_POST['dateFrom'] ?? "";;
        $filters['dateTo'] = $_POST['dateTo'] ?? "";;

        $chart = $this->statisticsModel->getPlayersByCountry($filters);

        echo json_encode($chart);
    }

    public function getPlayersByAge()
    {
        $filters['dateFrom'] = $_POST['dateFrom'] ?? "";;
        $filters['dateTo'] = $_POST['dateTo'] ?? "";;

        $chart = $this->statisticsModel->getPlayersByAge($filters);

        echo json_encode($chart);
    }

    public function getPlayersByGender()
    {
        $filters['dateFrom'] = $_POST['dateFrom'] ?? "";;
        $filters['dateTo'] = $_POST['dateTo'] ?? "";;

        $chart = $this->statisticsModel->getPlayersByGender($filters);

        echo json_encode($chart);
    }

    public function getTotalPlayers()
    {
        $filters['dateFrom'] = $_POST['dateFrom'] ?? "";;
        $filters['dateTo'] = $_POST['dateTo'] ?? "";;

        $chart = $this->statisticsModel->getPlayers($filters);

        echo json_encode($chart);
    }

    public function getNumberOfGames()
    {
        $filters['dateFrom'] = $_POST['dateFrom'] ?? "";;
        $filters['dateTo'] = $_POST['dateTo'] ?? "";;

        $chart = $this->statisticsModel->getNumberOfGames($filters);

        echo json_encode($chart);
    }

    public function getNumberOfActiveQuestions()
    {
        $filters['dateFrom'] = $_POST['dateFrom'] ?? "";;
        $filters['dateTo'] = $_POST['dateTo'] ?? "";;

        $chart = $this->statisticsModel->getNumberOfActiveQuestions($filters);

        echo json_encode($chart);
    }

    public function getNumberOfViwedSuggestions()
    {
        $filters['dateFrom'] = $_POST['dateFrom'] ?? "";;
        $filters['dateTo'] = $_POST['dateTo'] ?? "";;

        $chart = $this->statisticsModel->getNumberOfViwedSuggestions($filters);

        echo json_encode($chart);
    }

    public function getNumberOfTotalSuggestions()
    {
        $filters['dateFrom'] = $_POST['dateFrom'] ?? "";;
        $filters['dateTo'] = $_POST['dateTo'] ?? "";;

        $chart = $this->statisticsModel->getNumberOfTotalSuggestions($filters);

        echo json_encode($chart);
    }

    public function getPercentageOfEffectivenessPerUser()
    {
        $filters['dateFrom'] = $_POST['dateFrom'] ?? "";;
        $filters['dateTo'] = $_POST['dateTo'] ?? "";;

        $chart = $this->statisticsModel->getPercentageOfEffectivenessPerUser($filters);

        echo json_encode($chart);
    }

    public function getNewUsers()
    {
        $filters['dateFrom'] = $_POST['dateFrom'] ?? "";;
        $filters['dateTo'] = $_POST['dateTo'] ?? "";;

        $chart = $this->statisticsModel->getNewUsers($filters);

        echo json_encode($chart);
    }

    public function getPdf()
    {
        $data = json_decode($_POST['data'], true);

        $this->statisticsModel->getPDF($data);
    }
}