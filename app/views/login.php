<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <section class="w-full min-h-screen container mx-auto flex items-center justify-center">
        <div class="card shadow-md bg-base-300 w-1/3">
            <div class="card-body">
                <h2 class="card-title">Entrar</h2>
                <form class="space-y-4" action="/login" method="post">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">E-mail</legend>
                        <input name="email" type="text" class="input w-full" />
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Senha</legend>
                        <input name="password" type="password" class="input w-full" />
                    </fieldset>
                    <button class="btn btn-secondary w-full" type="submit">Entrar</button>
                    <div class="divider">OU</div>
                    <button class="btn btn-outline btn-secondary w-full">Registre-se</button>
                </form>
            </div>
        </div>
    </section>
</body>

</html>