<?php

class RegisterController {

    private $renderer;

    public function __construct($renderer) {
        $this->renderer = $renderer;
    }

    public function list() {
        $this->renderer->render('register');
    }
}