<?php
/**
 * @var int    $cartaId
 * @var float  $valor
 * @var string $publicKey
 * @var string $csp_nonce
 */
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pagamento - Carta do Coração</title>
  <link rel="stylesheet" href="/css/style.css">
  <!--
    Se você usa CSP via SecurityHeadersMiddleware, precisa liberar
    sdk.mercadopago.com e *.mercadopago.com em script-src, frame-src e
    connect-src, senão o Brick não carrega.
  -->
  <script src="https://sdk.mercadopago.com/js/v2" nonce="<?= htmlspecialchars($csp_nonce) ?>"></script>
</head>
<body class="bg-base-200 min-h-screen flex items-center justify-center p-4">

  <div class="card w-full max-w-md bg-base-100 shadow-xl">
    <div class="card-body">
      <h1 class="card-title text-2xl">Desbloqueie sua carta</h1>
      <p class="text-base-content/70">
        Valor: <span class="font-bold">R$ <?= number_format($valor, 2, ',', '.') ?></span>
      </p>

      <div id="paymentBrick_container" class="mt-4"></div>

      <div id="pix-result" class="hidden mt-4 text-center">
        <p class="font-semibold mb-2">Escaneie o QR Code para pagar com Pix</p>
        <img id="pix-qr-img" class="mx-auto rounded-lg w-48 h-48" alt="QR Code Pix">
        <button id="pix-copy-btn" class="btn btn-outline btn-sm mt-3">Copiar código Pix</button>
        <p class="text-sm text-base-content/60 mt-2">
          Assim que o pagamento for confirmado, esta página atualiza automaticamente.
        </p>
      </div>

      <div id="boleto-result" class="hidden mt-4 text-center">
        <p class="font-semibold mb-2">Seu boleto foi gerado</p>
        <a id="boleto-link" href="#" target="_blank" rel="noopener" class="btn btn-primary">Abrir boleto</a>
        <p class="text-sm text-base-content/60 mt-2">
          O pagamento pode levar até 2 dias úteis para ser confirmado.
        </p>
      </div>

      <div id="payment-error" class="hidden alert alert-error mt-4"></div>
    </div>
  </div>

  <script nonce="<?= htmlspecialchars($csp_nonce) ?>">
    window.CARTA_ID     = <?= (int) $cartaId ?>;
    window.MP_PUBLIC_KEY = "<?= htmlspecialchars($publicKey) ?>";
    window.CARTA_VALOR  = <?= (float) $valor ?>;
  </script>
  <script src="/js/pagamento.js" nonce="<?= htmlspecialchars($csp_nonce) ?>"></script>
</body>
</html>