<?php

use Dotenv\Dotenv;
use flight\Engine;

require_once __DIR__ . "/../services/MercadoPagoService.php";

$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

class PaymentController
{
    public $app;
    public MercadoPagoService $mp;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $mpConfig = $this->app->get('config');
        $this->mp = new MercadoPagoService($mpConfig['access_token']);
    }

    public function show(int $cartaId)
    {
        $stmt = Flight::db()->prepare("SELECT id, status from cartas where id = ?");
        $stmt->execute([$cartaId]);
        $carta = $stmt->fetch();

        if (!$carta) {
            $this->app->redirect('/cartas');
            return;
        }

        if ($carta['status'] === 'pago') {
            $this->app->redirect('/cartas');
            return;
        }

        $mpConfig = $this->app->get('config');
        $this->app->render('pagamento', [
            'csp_nonce' => $this->app->get('csp_nonce'),
            'cartaId' => $carta['id'],
            'valor' => $_ENV['PRECO_CARTA'],
            'publicKey' => $mpConfig['public_key']
        ]);
    }

    public function status(int $cartaId)
    {
        $stmt = Flight::db()->prepare('SELECT status FROM cartas WHERE id = ?');
        $stmt->execute([$cartaId]);
        $carta = $stmt->fetch();

        $this->app->json(['status' => $carta['status'] ?? 'desconhecido']);
    }

    public function webhook()
    {
        $body = json_decode($this->app->request()->getBody(), true);

        if (($body['type'] ?? null) !== 'payment') {
            http_response_code(200);
            return;
        }

        $paymentId = $body['data']['id'] ?? null;
        if (!$paymentId) {
            http_response_code(200);
            return;
        }

        try {
            $payment = $this->mp->searchPayment((int) $paymentId);
        } catch (\Throwable $e) {
            error_log('Erro ao buscar pagamento no webhook: ' . $e->getMessage());
            http_response_code(200);
            return;
        }

        $cartaId = (int) ($payment->external_reference ?? 0);
        if ($cartaId) {
            $statusInterno = MercadoPagoService::mapStatus($payment->status);
            $update = Flight::db()->prepare("UPDATE cartas SET status = ?, mp_payment_id = ?, WHERE id = ?");
            $update->execute([$statusInterno, $payment->id, $cartaId]);
        }

        http_response_code(200);
    }
}
