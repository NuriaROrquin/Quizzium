<?php

class Router
{

    private $configuration;
    private $defaultController;
    private $defaultMethod;

    public function __construct($configuration, $defaultController, $defaultMethod)
    {
        $this->configuration = $configuration;
        $this->defaultController = $defaultController;
        $this->defaultMethod = $defaultMethod;
    }

    public function route($module, $method)
    {

        $userIsOn = $this->security();

        switch ($module) {

            case "mail":
            case "register":
            case "login":

                if ( $userIsOn ) {
                    $module = "lobby";
                    $method = $this->defaultMethod;
                    $this->changeURL($module, $method);
                }
                break;

            case "lobby":
            case "profile":
            case "game":
            case "ranking":
            case "factory":
            case "report":
            case "admin":

                if ( !$userIsOn ) {
                    $module = "login";
                    $method = $this->defaultMethod;
                    $this->changeURL($module, $method);
                }
                break;

            default:

                if ( !$userIsOn ) {
                    $module = "login";
                }
                else{
                    $module = "lobby";
                }
                $method = $this->defaultMethod;
                $this->changeURL($module, $method);
                break;
        }

        $controller = $this->getControllerFrom($module);
        $this->executeMethodFromController($controller, $method);
    }

    private function changeURL($module, $method)
    {
        header("Location: /$module/$method");
        exit();
    }

    private function security()
    {
        $userIsOn = false;
        $fileToCompare = "./config/seguridad.txt";
        $cookie = empty($_COOKIE['seguridad']) ? false : $_COOKIE['seguridad'];

        if (file_exists($fileToCompare) && $cookie == file_get_contents($fileToCompare)) {
            $userIsOn = true;
        }
        return $userIsOn;
    }

    private function getControllerFrom($module)
    {
        $controllerName = 'get' . ucfirst($module) . 'Controller';
        $validController = method_exists($this->configuration, $controllerName) ? $controllerName : $this->defaultController;
        return call_user_func(array($this->configuration, $validController));
    }

    private function executeMethodFromController($controller, $method)
    {
        $validMethod = method_exists($controller, $method) ? $method : $this->defaultMethod;
        call_user_func(array($controller, $validMethod));
    }
}