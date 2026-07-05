<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" href="/images/logo.png" type="image/png">
</head>

<body>
    <section class="w-full min-h-screen container mx-auto flex items-center justify-center">
        <div class="card shadow-md bg-base-300 w-full max-w-md mx-4">
            <div class="card-body">
                <img src="/images/logo_escrito.png" class="h-20 mb-4 self-center" />
                <h2 class="card-title self-center">Entrar</h2>
                <p class="self-center">Entre com suas credenciais</p>
                <form class="space-y-4" action="/login" method="post">
                    <fieldset class="fieldset hidden">
                        <legend for="name" class="fieldset-legend">Nome</legend>
                        <input name="name" type="text" id="name" class="input w-full" />
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend for="email" class="fieldset-legend">E-mail</legend>
                        <input autocomplete="email" name="email" type="email" id="email" required class="input w-full" />
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend for="password" class="fieldset-legend">Senha</legend>
                        <input name="password" type="password" id="password" required class="input w-full" />
                    </fieldset>
                    <button class="btn btn-secondary w-full" type="submit">Entrar</button>
                    <div class="divider">OU</div>
                    <button type="button" id="btn-register" class="btn btn-outline btn-secondary w-full">Criar conta</button>
                </form>
            </div>
        </div>
    </section>

    <script src="/js/auth.js" nonce="<?= htmlspecialchars($csp_nonce ?? '') ?>"></script>
</body>

</html>