<?php

use flight\Engine;
use Dotenv\Dotenv;

require_once __DIR__ . "/../services/GeminiChat.php";

$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

const TOTAL_PERGUNTAS = 7;

class ChatController
{
    private $app;

    public function __construct(Engine $app)
    {
        $this->app = $app;
    }

    public function send()
    {
        $result = $this->processarMensagem();
        $this->app->json($result);
    }

    private function processarMensagem()
    {
        $gemini = new GeminiChat($_ENV['GEMINI_KEY']);
        $resposta = trim(Flight::request()->data->resposta ?? '');
        $turno = $_SESSION['turno'] ?? 0;

        try {
            if ($turno === 0) {
                $resultado = $gemini->primeiraPergunta();
                $_SESSION['interaction_id'] = $resultado['interaction_id'];
                $_SESSION['turno'] = 1;

                return [
                    'tipo' => 'pergunta',
                    'texto' => $resultado['text'],
                    'progresso' => 1,
                    'total' => TOTAL_PERGUNTAS,
                ];
            }

            if ($turno >= TOTAL_PERGUNTAS) {
                $resultado = $gemini->gerarCartaFinal($resposta, $_SESSION['interaction_id']);

                $stmt = Flight::db()->prepare("INSERT INTO cartas (session_id, texto_carta) VALUES (?, ?)");

                $stmt->execute([session_id(), $resultado['text']]);
                $cartaId = Flight::db()->lastInsertId();

                $_SESSION['carta_id'] = $cartaId;

                return [
                    'tipo' => 'final',
                    'carta_id' => $cartaId,
                ];
            }

            $resultado = $gemini->proximaPergunta($resposta, $_SESSION['interaction_id']);
            $_SESSION['interaction_id'] = $resultado['interaction_id'];
            $_SESSION['turno'] = $turno + 1;

            return [
                'tipo' => 'pergunta',
                'texto' => $resultado['text'],
                'progresso' => $turno + 1,
                'total' => TOTAL_PERGUNTAS,
            ];
        } catch (\RuntimeException $e) {
            return ['tipo' => 'erro', 'texto' => 'Algo deu errado. Tente novamente.'];
        }
    }
}
