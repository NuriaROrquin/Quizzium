<?php
include_once('helpers/MySqlDatabase.php');
include_once("helpers/MustacheRender.php");
include_once('helpers/Router.php');

include_once('model/LoginModel.php');
include_once('model/LobbyModel.php');
include_once('model/RegisterModel.php');
include_once('model/MailModel.php');
include_once('model/ProfileModel.php');

include_once('controller/LobbyController.php');
include_once('controller/LoginController.php');
include_once('controller/RegisterController.php');
include_once('controller/MailController.php');
include_once('controller/ProfileController.php');

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
            new LobbyModel(
                $this->getDatabase()),
            $this->getRenderer());
    }

    public function getPerfilController()
    {
        return new PerfilController(
            new PerfilModel(
                $this->getDatabase()),
            $this->getRenderer());
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
            new MailModel($this->getDatabase()),
            $this->getRenderer()
        );
    }

    public function getProfileController()
    {
        return new ProfileController($this->getRenderer(), new ProfileModel(
            $this->getDatabase()));
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