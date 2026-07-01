<?php

use flight\Engine;

class AuthController {
    private Engine $app;

    public function __construct($app) {
        $this->app = $app;
    }

    public function login() {
        $data = $this->app->request()->data->getData();

        $email = $data["email"];
        $password = $data["password"];

        $stmt = Flight::db()->prepare("SELECT * FROM users where email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        if ($row) {
            
        }
    }

    public function register() {
        $data = $this->app->request()->data->getData();
    }
}