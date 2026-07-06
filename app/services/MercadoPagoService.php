<?php

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Common\RequestOptions;


class MercadoPagoService
{
    private PaymentClient $paymentClient;

    public function __construct(string $accessToken)
    {
        MercadoPagoConfig::setAccessToken($accessToken);
        $this->paymentClient = new PaymentClient();
    }

    /**
     * Cria um pagamento a partir do formData que o Payment Brick devolve no
     * front-end (cartão, pix ou boleto). $extra são os campos que SÓ o
     * servidor deve controlar (valor, descrição, referência, webhook).
     * Nunca confie no valor que vier do front-end.
     */
    public function createPayment(array $formData, array $extra = []): object
    {
        $requestData = array_merge($formData, $extra);

        $requestOptions = new RequestOptions();
        // Evita cobrança duplicada se o Brick reenviar a mesma requisição
        // (ex: usuário clica duas vezes, timeout de rede, etc).
        $requestOptions->setCustomHeaders([
            'X-Idempotency-Key: ' . bin2hex(random_bytes(16))
        ]);

        return $this->paymentClient->create($requestData, $requestOptions);
    }

    public function searchPayment(int $paymentId): object
    {
        return $this->paymentClient->get($paymentId);
    }

    public function mapStatus(string $mpStatus): string
    {
        return match ($mpStatus) {
            'approved' => 'pago',
            'pending', 'in_process' => 'pendente',
            'rejected' => 'rejeitado',
            'cancelled', 'refunded', 'charge_back' => 'cancelado',
            default => 'pendente'
        };
    }
}
