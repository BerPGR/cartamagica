<header class="navbar bg-base-200 border-b border-base-300 px-6 lg:px-12 sticky top-0 z-50">

    <div class="navbar-start">
        <a href="/" class="text-2xl font-bold text-secondary">
            <img src="/images/logo.png" class="w-10" />
        </a>
    </div>

    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal gap-2">

            <?php if ($_SERVER['REQUEST_URI'] === '/') { ?>
                <li>
                    <a href="#como-funciona">
                        Como funciona
                    </a>
                </li>

                <li>
                    <a href="#beneficios">
                        Benefícios
                    </a>
                </li>
            <?php } else { ?>
                <li>
                    <a href="/home">
                        Home
                    </a>
                </li>

                <li>
                    <a href="/cartas">
                        Minhas Cartas
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>

    <div class="navbar-end gap-3">

        <a href="/login" class="btn btn-ghost" id="btn-login">
            Entrar
        </a>

        <a href="/create" class="btn btn-secondary rounded-xl">
            Criar Carta
        </a>

    </div>

    <script type="module" src="/js/header.js" nonce="<?= htmlspecialchars($csp_nonce ?? '') ?>"></script>
</header>