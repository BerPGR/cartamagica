<?php

use flight\Engine;
require_once __DIR__ . "/../services/jwtservice.php";

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
        if ($row && password_verify($password, $row["password"])) {
            $jwtService = new JWTService();
            $token = $jwtService->generate([
                'sub' => $row["id"],
                'email' => $row["email"],
                'name' => $row["name"],
            ]);
            return $this->app->json(['token' => $token], 200);
        } else {
            return $this->app->json(['error' => 'Invalid credentials'], 401);
        }
    }

    public function register() {
        $data = $this->app->request()->data->getData();
    }
}