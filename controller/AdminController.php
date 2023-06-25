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

        $dateFrom = $data['dateFrom'];
        $dateTo = $data['dateTo'];
        $totalPlayers = $data['total_players'];
        $totalGames = $data['total_games'];
        $totalQuestionsActive = $data['total_questions_active'];
        $totalSuggestions = $data['total_suggestions'];
        $totalViewedSuggestions = $data['total_viwed_suggestions'];
        $percentageEffectiveForPlayer = $data['percentage_effective_for_player'];
        $totalNewUsers = $data['total_new_users'];
        $byCountry = $data['by_country'];
        $image = $data['by_country'];
        $image = $data['by_age'];
        $image = $data['by_gender'];

        $this->statisticsModel->getPDF($data);
    }
}