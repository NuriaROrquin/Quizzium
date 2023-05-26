<?php
include_once('helpers/MySqlDatabase.php');
include_once("helpers/MustacheRender.php");
include_once('helpers/Router.php');

include_once('model/LoginModel.php');
include_once('model/RegisterModel.php');

include_once('controller/ToursController.php');
include_once('controller/SongsController.php');
include_once('controller/LobbyController.php');
include_once('controller/LoginController.php');
include_once('controller/RegisterController.php');

include_once('third-party/mustache/src/Mustache/Autoloader.php');


class Configuration
{
    private $configFile = 'config/config.ini';

    public function __construct()
    {
    }

    /*public function getToursController() {
        return new ToursController(
            new ToursModel($this->getDatabase()),
            $this->getRenderer());
    }

    public function getSongsController() {
        return new SongsController(
            new SongsModel($this->getDatabase()),
            $this->getRenderer());
    }*/

    public function getLobbyController()
    {
        return new LobbyController($this->getRenderer());
    }

    public function getLoginController()
    {
        return new LoginController(
            new LoginModel(
                $this->getDatabase()),
            $this->getRenderer());

        //return new LoginController($this->getRenderer());
    }

    public function getRegisterController()
    {
        return new RegisterController(
            new RegisterModel(
                $this->getDatabase()),
            $this->getRenderer());
    }

    private function getArrayConfig()
    {
        return parse_ini_file($this->configFile);
    }

    private function getRenderer()
    {
        return new MustacheRender('view/partial');
    }

    public function getDatabase()
    {
        $config = $this->getArrayConfig();
        return new MySqlDatabase(
            $config['servername'],
            $config['username'],
            $config['password'],
            $config['database']);
    }

    public function getRouter()
    {
        return new Router(
            $this,
            "getRegisterController",
            "list");
    }
}