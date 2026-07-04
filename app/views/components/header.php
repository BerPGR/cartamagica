<header class="navbar bg-base-300 shadow-md px-6 lg:px-12 sticky top-0 z-50">

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