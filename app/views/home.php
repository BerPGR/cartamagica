<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div class="bg-base-200 w-full h-screen">
        <?php include __DIR__ . '/components/header.php'; ?>
        <div class="container mx-auto flex justify-center mx-10 my-10">
            <div class="card shadow-xs max-w-3/5 w-full bg-red-500">
                <div class="card-body">
                    <h1 class="card-title"></h1>
                    <div>
                        <div class="divider divider-secondary"></div>
                        <h1 class="tex"></h1>
                    </div>
                    <div class="card-action justify-between">
                        <button class="btn btn-danger" type="button" onclick="logout()">Sair da conta</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module" src="/js/header.js" nonce="<?= htmlspecialchars($csp_nonce ?? '') ?>"></script>
    <script type="module" src="/js/home.js" nonce="<?= htmlspecialchars($csp_nonce ?? '') ?>"></script>
</body>

</html>