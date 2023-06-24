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
        $chart = $this->statisticsModel->getPlayersByCountry();

        echo json_encode($chart);
    }

    public function getPlayersByAge()
    {
        $chart = $this->statisticsModel->getPlayersByAge();

        echo json_encode($chart);
    }
}