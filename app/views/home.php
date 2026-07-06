<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" href="/images/logo.png" type="image/png">
</head>

<body>
    <div class="bg-base-200 w-full h-screen">
        <?php include __DIR__ . '/components/header.php'; ?>
        <div class="container mx-auto flex justify-center px-4 my-10">
            <div class="card shadow-xs max-w-4xl w-full">
                <div class="card-body">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div>
                            <h1 class="card-title">Minhas Cartas</h1>
                            <p class="text-sm opacity-70" id="cartas-count">Carregando...</p>
                        </div>
                        <a href="/create" class="btn btn-secondary btn-outline">+ Criar carta</a>
                    </div>

                    <div class="divider divider-secondary"></div>

                    <div id="loading-state" class="flex justify-center py-10">
                        <span class="loading loading-spinner loading-lg text-secondary"></span>
                    </div>

                    <div id="empty-state" class="hidden flex-col items-center gap-4 py-10 text-center">
                        <p class="text-lg opacity-70">Você ainda não criou nenhuma carta.</p>
                        <a href="/create" class="btn btn-secondary">Criar minha primeira carta</a>
                    </div>

                    <div id="table-wrapper" class="hidden overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Status</th>
                                    <th>Criada em</th>
                                    <th class="text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="cartas-tbody">
                            </tbody>
                        </table>
                    </div>

                    <div class="divider divider-secondary"></div>
                    <div class="card-action flex items-center justify-between">
                        <button class="btn btn-error" type="button" id="btn-logout">Sair da conta</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module" src="/js/header.js" nonce="<?= htmlspecialchars($csp_nonce ?? '') ?>"></script>
    <script type="module" src="/js/home.js" nonce="<?= htmlspecialchars($csp_nonce ?? '') ?>"></script>
</body>

</html>