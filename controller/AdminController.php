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
        $chart['datos'] = [
            ['Argentina', 5],
            ['Peru', 2],
            ['Brasil', 1],
        ];

        $this->renderer->render("admin", $chart);
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
}