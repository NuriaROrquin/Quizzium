<?php

class ToursController {
    private $toursModel;
    private $renderer;

    public function __construct($toursModel, $renderer) {
        $this->toursModel = $toursModel;
        $this->renderer = $renderer;
    }

    public function list() {
        $data["presentaciones"] = $this->toursModel->getTours();
        $this->renderer->render("tours", $data);
    }
}