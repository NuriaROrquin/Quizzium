<?php
include_once('helpers/MySqlDatabase.php');
include_once("helpers/MustacheRender.php");
include_once('helpers/Router.php');
include_once('helpers/Logger.php');

include_once('model/LoginModel.php');
include_once('model/RegisterModel.php');
include_once('model/MailModel.php');
include_once('model/ProfileModel.php');
include_once('model/GameModel.php');
include_once('model/RankingModel.php');
include_once('model/LobbyModel.php');
include_once('model/FactoryModel.php');
include_once('model/ReportModel.php');
include_once('model/StatisticsModel.php');

include_once('controller/LobbyController.php');
include_once('controller/LoginController.php');
include_once('controller/RegisterController.php');
include_once('controller/MailController.php');
include_once('controller/ProfileController.php');
include_once('controller/GameController.php');
include_once('controller/RankingController.php');
include_once('controller/FactoryController.php');
include_once('controller/ReportController.php');
include_once('controller/AdminController.php');

include_once('third-party/mustache/src/Mustache/Autoloader.php');


class Configuration
{
    private $configFile = 'config/config.ini';

    public function __construct()
    {
    }

    public function getLobbyController()
    {
        return new LobbyController(
            $this->getRenderer(),
            new LobbyModel(
                $this->getDatabase())
        );
    }

    public function getLoginController()
    {
        return new LoginController(
            new LoginModel(
                $this->getDatabase()),
            $this->getRenderer()
        );
    }

    public function getRegisterController()
    {
        return new RegisterController(
            new RegisterModel(
                $this->getDatabase()),
            $this->getRenderer()
        );
    }

    public function getMailController()
    {
        return new MailController(
            new MailModel($this->getDatabase())
        );
    }

    public function getProfileController()
    {
        return new ProfileController(
            $this->getRenderer(),
            new ProfileModel($this->getDatabase())
        );
    }

    public function getGameController()
    {
        return new GameController(
            $this->getRenderer(),
            new GameModel($this->getDatabase())
        );
    }

    public function getRankingController()
    {
        return new RankingController(
            $this->getRenderer(),
            new RankingModel($this->getDatabase()),
            new ProfileModel($this->getDatabase())
        );
    }

    public function getFactoryController()
    {
        return new FactoryController(
            $this->getRenderer(),
            new FactoryModel($this->getDatabase()),
            new ProfileModel($this->getDatabase())
        );
    }

    public function getReportController()
    {
        return new ReportController(
            $this->getRenderer(),
            new ReportModel($this->getDatabase()),
            new ProfileModel($this->getDatabase())
        );
    }

    public function getAdminController()
    {
        return new AdminController(
            $this->getRenderer(),
            new StatisticsModel($this->getDatabase())
        );
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
            "getLoginController",
            "list");
    }
}