<?php

use Dotenv\Dotenv;
use flight\Engine;
use MercadoPago\Exceptions\MPApiException;

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
        $mpConfig = $this->app->get('mp_config');
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

        $mpConfig = $this->app->get('mp_config');
        $this->app->render('pagamento', [
            'csp_nonce' => $this->app->get('csp_nonce'),
            'cartaId' => $carta['id'],
            'valor' => $_ENV['PRECO_CARTA'],
            'publicKey' => $mpConfig['public_key']
        ]);
    }

    /**
     * POST /pagamento/processar
     * Recebe o formData do Payment Brick (cartão, pix ou boleto) e cria o
     * pagamento via API do Mercado Pago.
     */
    public function process(): void
    {
        $body = json_decode($this->app->request()->getBody(), true);
        $cartaId = (int) ($body['cartaId'] ?? 0);
        $formData = $body['formData'] ?? null;

        if (!$cartaId || !$formData) {
            $this->app->json(['error' => 'Dados Inválidos'], 400);
            return;
        }

        $stmt = Flight::db()->prepare('SELECT id, status FROM cartas WHERE id = ?');
        $stmt->execute([$cartaId]);
        $carta = $stmt->fetch();


        if (!$carta) {
            $this->app->json(['error' => 'Carta não encontrada'], 404);
            return;
        }

        if ($carta['status'] === 'pago') {
            $this->app->json(['error' => "Carta já está paga"], 409);
            return;
        }

        $baseUrl = 'https://atrocious-kindness-profanity.ngrok-free.dev';

        try {
            $payment = $this->mp->createPayment($formData, [
                'transaction_amount' => (float) $_ENV['PRECO_CARTA'],
                'description' => "Carta do Coração #{$cartaId}",
                'external_reference' => (string) $cartaId,
                'notification_url' => $baseUrl . '/webhook/mercadopago'
            ]);
        } catch (MPApiException $e) {
            $apiResponse = $e->getApiResponse();
            $this->app->json([
                'error' => 'Pagamento recusado',
                'detalhe' => $apiResponse ? $apiResponse->getContent() : $e->getMessage()
            ], 400);
            return;
        } catch (\Throwable $e) {
            // TEMPORÁRIO, só pra debugar — remove depois
            $this->app->json([
                'error' => 'Erro interno',
                'detalhe' => $e->getMessage(),
                'arquivo' => $e->getFile() . ':' . $e->getLine()
            ], 500);
            return;
        }

        $statusInterno = MercadoPagoService::mapStatus($payment->status);
        $update = Flight::db()->prepare("UPDATE cartas SET status = ?, mp_payment_id = ? WHERE id = ?");
        $update->execute([$statusInterno, $payment->id, $cartaId]);

        $resposta = [
            'status' => $payment->status,
            'status_interno' => $statusInterno,
            'payment_method_id' => $payment->payment_method_id
        ];

        if ($payment->payment_method_id === 'pix') {
            $resposta['qr_code'] = $payment->point_of_interaction->transaction_data->qr_code ?? null;
            $resposta['qr_code_base64'] = $payment->point_of_interaction->transaction_data->qr_code_base64 ?? null;
        }

        if (in_array($payment->payment_method_id, ['bolbradesco', 'boleto'], true)) {
            $resposta['boleto_url'] = $payment->transaction_details->external_resource_url ?? null;
            $resposta['barcode'] = $payment->barcode->content ?? null;
        }

        $this->app->json($resposta);
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
            $update = Flight::db()->prepare("UPDATE cartas SET status = ?, mp_payment_id = ? WHERE id = ?");
            $update->execute([$statusInterno, $payment->id, $cartaId]);
        }

        http_response_code(200);
    }
}
