<?php

class ToursModel {

    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getTours() {
        return $this->database->query('SELECT * FROM presentaciones');
    }
}