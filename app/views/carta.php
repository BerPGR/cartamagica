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

        <div id="loading-state" class="flex flex-col gap-4 w-full">
            <div class="skeleton h-8 w-1/2 mx-auto"></div>
            <div class="skeleton h-40 w-full"></div>
        </div>

        <div id="error-state" class="alert alert-error hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <span id="error-message">Não foi possível carregar a carta.</span>
        </div>

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

        <div id="carta-card" class="card bg-base-100 shadow-xl hidden">
            <div class="card-body">
                <div class="flex items-center justify-between mb-2">
                    <span class="badge badge-success badge-sm">Confirmada</span>
                    <span class="text-xs text-base-content/50" id="carta-data"></span>
                </div>

                <div class="flex flex-wrap gap-4 items-end mb-4 p-3 rounded-lg bg-base-200/50">
                    <div class="form-control">
                        <label class="label py-0"><span class="label-text text-xs">Fonte</span></label>
                        <select id="select-fonte" class="select select-bordered select-sm">
                            <option value="font-serif" selected>Clássica</option>
                            <option value="font-mono">Máquina de escrever</option>
                            <option value="font-caveat">Manuscrita</option>
                            <option value="font-sans">Moderna</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label py-0"><span class="label-text text-xs">Fundo</span></label>
                        <div class="flex gap-2" id="fundo-swatches">
                            <button type="button"
                                class="w-6 h-6 rounded-full border-2 border-primary bg-[#ffffff] ring ring-primary ring-offset-2"
                                data-bg="bg-[#ffffff]" data-text="text-[#171717]" title="Branco"></button>

                            <button type="button"
                                class="w-6 h-6 rounded-full border-2 border-base-content/20 bg-[#fffbeb]"
                                data-bg="bg-[#fffbeb]" data-text="text-[#451a03]" title="Papel envelhecido"></button>

                            <button type="button"
                                class="w-6 h-6 rounded-full border-2 border-base-content/20 bg-[#fff1f2]"
                                data-bg="bg-[#fff1f2]" data-text="text-[#4c0519]" title="Rosa suave"></button>

                            <button type="button"
                                class="w-6 h-6 rounded-full border-2 border-base-content/20 bg-[#0f172a]"
                                data-bg="bg-[#0f172a]" data-text="text-[#f1f5f9]" title="Escuro"></button>
                        </div>
                    </div>

                    <button id="btn-baixar-pdf" type="button" class="btn btn-primary btn-sm ml-auto">
                        Baixar em PDF
                    </button>
                </div>

                <div id="carta-render" class="rounded-lg p-6 transition-colors bg-white text-neutral-900">
                    <div id="carta-texto" class="whitespace-pre-line leading-relaxed font-serif"></div>
                </div>
            </div>
        </div>

    </main>

    <script type="module" src="/js/carta.js" nonce="<?= htmlspecialchars($csp_nonce ?? '') ?>"></script>
</body>

</html>