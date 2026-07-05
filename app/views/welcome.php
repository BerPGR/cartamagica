<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta do Coração</title>

    <link href="/css/style.css" rel="stylesheet" />
</head>

<body class="bg-base-100">

    <?php include __DIR__ . "/components/header.php"; ?>

    <section class="hero min-h-[85vh]">
        <div class="hero-content flex-col lg:flex-row-reverse gap-20">

            <div class="lg:w-1/2">
                <img
                    src="/images/family.png"
                    class="rounded-3xl shadow-2xl"
                    alt="Família">
            </div>

            <div class="lg:w-1/2">

                <div class="badge badge-secondary badge-outline mb-4">
                    ❤️ Para qualquer ocasião especial
                </div>

                <h1 class="text-6xl font-extrabold leading-tight">
                    Transforme suas memórias em uma
                    <span class="text-secondary">
                        carta inesquecível
                    </span>
                </h1>

                <p class="py-8 text-lg opacity-80">
                    Nossa inteligência artificial cria uma carta emocionante,
                    personalizada e única baseada nas histórias que você contar.
                </p>

                <div class="flex gap-4 flex-wrap">

                    <a href="/create" class="btn btn-secondary btn-lg">
                        Criar minha carta
                    </a>

                    <button class="btn btn-outline btn-lg">
                        Ver exemplo
                    </button>

                </div>

                <div class="stats shadow mt-10">

                    <div class="stat">
                        <div class="stat-figure">

                        </div>
                        <div class="stat-title">Cartas criadas</div>
                        <div class="stat-value text-secondary">5.000+</div>
                    </div>

                    <div class="stat">
                        <div class="stat-figure">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="50" height="50" viewBox="0 0 128 128">
                                <path d="M 64 13.5 C 36.2 13.5 13.5 36.2 13.5 64 C 13.5 91.8 36.2 114.5 64 114.5 C 91.8 114.5 114.5 91.8 114.5 64 C 114.5 36.2 91.8 13.5 64 13.5 z M 64 19.5 C 88.5 19.5 108.5 39.5 108.5 64 C 108.5 88.5 88.5 108.5 64 108.5 C 39.5 108.5 19.5 88.5 19.5 64 C 19.5 39.5 39.5 19.5 64 19.5 z M 64 31 C 62.3 31 61 32.3 61 34 L 61 58.800781 C 59.2 59.800781 58 61.8 58 64 C 58 67.3 60.7 70 64 70 C 66.2 70 68.199219 68.8 69.199219 67 L 84 67 C 85.7 67 87 65.7 87 64 C 87 62.3 85.7 61 84 61 L 69.199219 61 C 68.699219 60.1 67.9 59.300781 67 58.800781 L 67 34 C 67 32.3 65.7 31 64 31 z"></path>
                            </svg>
                        </div>
                        <div class="stat-title">Tempo</div>
                        <div class="stat-value">2 min</div>
                        <div class="stat-desc">Pronto em poucos minutos!</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Como funciona -->
    <section id="como-funciona" class="py-24 bg-base-200">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-14">
                Como funciona?
            </h2>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body items-center text-center">
                        <div class="text-5xl">
                            💬
                        </div>
                        <h3 class="card-title">
                            Conte sua história
                        </h3>
                        <p>
                            Converse com nosso assistente e compartilhe suas melhores lembranças.
                        </p>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body items-center text-center">
                        <div class="text-5xl">
                            🤖
                        </div>

                        <h3 class="card-title">
                            IA cria sua carta
                        </h3>

                        <p>
                            Nossa IA transforma suas histórias em uma homenagem emocionante.
                        </p>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body items-center text-center">
                        <div class="text-5xl">
                            📄
                        </div>

                        <h3 class="card-title">
                            Baixe e compartilhe
                        </h3>

                        <p>
                            Receba sua carta em PDF ou compartilhe com quem você ama.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefícios -->
    <section id="beneficios" class="py-24">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-14">
                Por que usar?
            </h2>

            <div class="grid md:grid-cols-4 gap-8">
                <div class="card bg-base-100 border">
                    <div class="card-body">
                        <h3 class="font-bold">
                            ❤️ Personalizada
                        </h3>

                        <p>
                            Cada carta é única.
                        </p>
                    </div>
                </div>

                <div class="card bg-base-100 border">
                    <div class="card-body">
                        <h3 class="font-bold">
                            ⚡ Rápida
                        </h3>

                        <p>
                            Menos de 2 minutos.
                        </p>
                    </div>
                </div>

                <div class="card bg-base-100 border">
                    <div class="card-body">
                        <h3 class="font-bold">
                            📱 Compartilhável
                        </h3>

                        <p>
                            WhatsApp, PDF e redes sociais.
                        </p>
                    </div>
                </div>

                <div class="card bg-base-100 border">
                    <div class="card-body">
                        <h3 class="font-bold">
                            🔒 Privada
                        </h3>

                        <p>
                            Suas histórias são protegidas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->

    <section class="py-20 bg-secondary text-secondary-content">
        <div class="text-center">
            <h2 class="text-5xl font-bold">
                Pronto para emocionar alguém?
            </h2>

            <p class="mt-6 text-xl">
                Crie sua carta em poucos minutos.
            </p>

            <a href="/create" class="btn btn-neutral btn-lg mt-10">
                Começar agora
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer footer-center bg-base-200 text-base-content p-10">
        <div>
            <h2 class="font-bold text-lg">
                Carta do Coração
            </h2>

            <p>
                Criando memórias inesquecíveis através da inteligência artificial.
            </p>
        </div>

        <nav class="grid grid-flow-col gap-6">
            <a href="/privacy" class="link link-hover">
                Privacidade
            </a>

            <a href="/terms" class="link link-hover">
                Termos
            </a>
        </nav>
    </footer>
</body>
</html>