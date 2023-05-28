<?php
session_start();
class LobbyModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

}



