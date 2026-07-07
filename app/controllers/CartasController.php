<?php

use flight\Engine;

class CartasController
{
    private $app;

    public function __construct(Engine $app)
    {
        $this->app = $app;
    }

    public function index($userId)
    {
        $stmt = Flight::db()->prepare("SELECT * FROM cartas where user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetchAll();

        $this->app->json($result);
    }

    public function show($cartaId)
    {
        $stmt = Flight::db()->prepare(
            "SELECT id, texto_carta, status, criado_em FROM cartas WHERE id = ?"
        );
        $stmt->execute([$cartaId]);
        $result = $stmt->fetch();

        if (!$result) {
            Flight::halt(404, json_encode(['error' => 'Carta não encontrada']));
            return;
        }

        $this->app->json($result);
    }
}
