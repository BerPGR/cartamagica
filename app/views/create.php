<!DOCTYPE html>
<html lang="pt-br" data-theme="light">

<head>
    <meta charset="UTF-8">
    <title>Criar carta</title>
    <link href="/css/style.css" rel="stylesheet">
    <link rel="icon" href="/images/logo.png" type="image/png">
</head>

<body class="min-h-screen bg-base-200 flex items-center justify-center p-4">

    <div class="card w-full max-w-2xl bg-base-100 shadow-xl">
        <div class="card-body p-0">

            <div id="conteudo-chat">
                <div class="p-4 border-b border-base-200">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="font-semibold text-lg">Vamos criar sua carta</h2>
                        <span id="progresso-texto" class="text-sm text-base-content/60">0/7</span>
                    </div>
                    <progress id="progresso-bar" class="progress progress-primary w-full" value="0" max="7"></progress>
                </div>

                <div id="chat" class="p-4 space-y-2 overflow-y-auto" style="height: 60vh;"></div>

                <div id="loading" class="px-4 pb-2 hidden">
                    <span class="loading loading-dots loading-sm"></span>
                </div>

                <div id="input-area" class="p-4 border-t border-base-200 flex gap-2">
                    <input type="text" id="msg" placeholder="Digite sua resposta..." class="input input-bordered flex-1" autocomplete="off">
                    <button id="btn-enviar" class="btn btn-primary">Enviar</button>
                </div>
            </div>

            <div id="tela-status" class="hidden p-12 flex flex-col items-center justify-center text-center" style="height: 60vh;">
                <div id="status-gerando">
                    <span class="loading loading-spinner loading-lg text-primary"></span>
                    <p class="mt-4 text-base-content/70">Estamos criando sua carta...</p>
                </div>

                <div id="status-pronto" class="hidden">
                    <div class="text-5xl mb-2">✅</div>
                    <p class="text-base-content/70">Sua carta está pronta!</p>
                </div>
            </div>

        </div>
    </div>

    <script type='module' src="/js/chat.js" nonce="<?= htmlspecialchars($csp_nonce ?? '') ?>"></script>
</body>

</html>