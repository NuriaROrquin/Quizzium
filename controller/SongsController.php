<?php

class SongsController {
    private $songsModel;
    private $renderer;

    public function __construct($songsModel, $renderer) {
        $this->songsModel = $songsModel;
        $this->renderer = $renderer;
    }

    public function list() {
        $data["canciones"] = $this->songsModel->getSongs();
        $this->renderer->render("songs", $data);
    }

    public function add(){
        die("llame a add");
    }

    public function delete() {
        die('llame a delete');
    }
}