<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha carta</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body class="w-full min-h-screen bg-base-200">
    <?php include __DIR__ . "/components/header.php"; ?>

    <main class="container mx-auto max-w-xl p-4 md:p-8 w-full">

        <!-- Skeleton enquanto carrega -->
        <div id="loading-state" class="flex flex-col gap-4 w-full">
            <div class="skeleton h-8 w-1/2 mx-auto"></div>
            <div class="skeleton h-40 w-full"></div>
        </div>

        <!-- Estado de erro (404, falha de rede) -->
        <div id="error-state" class="alert alert-error hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <span id="error-message">Não foi possível carregar a carta.</span>
        </div>

        <!-- Carta ainda não liberada (pagamento pendente/recusado) -->
        <div id="pending-state" class="card bg-base-100 shadow-xl hidden">
            <div class="card-body items-center text-center">
                <div class="text-5xl mb-2">✉️</div>
                <h2 class="card-title" id="pending-title">Aguardando confirmação</h2>
                <p class="text-base-content/70" id="pending-message">
                    Assim que o pagamento for confirmado, sua carta aparece aqui.
                </p>
                <div class="badge badge-lg mt-2" id="pending-badge"></div>
            </div>
        </div>

        <!-- Carta liberada -->
        <div id="carta-card" class="card bg-base-100 shadow-xl hidden">
            <div class="card-body">
                <div class="flex items-center justify-between mb-2">
                    <span class="badge badge-success badge-sm">Confirmada</span>
                    <span class="text-xs text-base-content/50" id="carta-data"></span>
                </div>
                <div id="carta-texto" class="whitespace-pre-line leading-relaxed font-serif text-base-content/90"></div>
            </div>
        </div>

    </main>

    <script type="module" src="/js/carta.js" nonce="<?= htmlspecialchars($csp_nonce ?? '') ?>"></script>
</body>

</html>