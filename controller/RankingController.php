<?php

class RankingController
{
    private $renderer;
    private $rankingModel;
    private $profileModel;

    public function __construct($renderer, $rankingModel, $profileModel)
    {
        $this->renderer = $renderer;
        $this->rankingModel = $rankingModel;
        $this->profileModel = $profileModel;
    }

    public function list(){

        $mail = $_SESSION['user'];

        $id_cuenta = $this->profileModel->getID($mail);

        //$data["owner"] = $this->profileModel->getProfile($id_cuenta);

        $data["rankingList"] = $this->rankingModel->getRanking();

        var_dump($data['rankingList']);

        $this->renderer->render('ranking', $data);
    }

}
