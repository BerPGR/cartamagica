<?php

use flight\Engine;

class CartasController {
    private $app;

    public function __construct(Engine $app) {
        $this->app = $app;
    }

    public function index($userId) {
        $stmt = Flight::db()->prepare("SELECT * FROM cartas where user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetchAll();

        $this->app->json($result);
    }
}