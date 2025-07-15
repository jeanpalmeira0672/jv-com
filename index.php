<?php
session_start();
require_once 'db.php'; // PDO $pdo configurado

// Processa login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $email = trim($_POST['email-login']  ?? '');
    $senha = $_POST['senha-login'] ?? '';
    $errors = [];

    if (!$email || !$senha) {
        $errors[] = 'Preencha e-mail e senha.';
    } else {
        // ADMIN hardcoded
        if ($email === 'admin@gmail.com' && $senha === 'admin') {
            $_SESSION['user_id']   = 0;
            $_SESSION['user_name'] = 'admin';
            $_SESSION['user_role'] = 'admin';
            header('Location: main-admin.php');
            exit;
        }
        // usuário normal
        $stmt = $pdo->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['user_role'] = 'user';
            header('Location: main.php');
            exit;
        } else {
            $errors[] = 'E-mail ou senha incorretos.';
        }
    }
}

// Busca produtos (incluindo imagem da pasta uploads)
$stmt = $pdo->prepare("SELECT id, nome, preco, imagem, estoque FROM produtos LIMIT 16");
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FéNotebooks.com</title>
    <link rel="icon" href="images/logo.png">
    <link rel="stylesheet" href="css/style2.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php if (!empty($errors)): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: 'Ops...',
                text: <?= json_encode(implode("\n", $errors)) ?>,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>

    <!-- NAVBAR -->
    <header id="header">
        <nav class="nav">
            <ul class="nav__list">
                <li><a href="#hero">Início</a></li>
                <li><a href="#products">Destaques</a></li>
                <li><a href="#categories">Categorias</a></li>
            </ul>

            <!-- SEARCHBAR COM ÍCONE -->
            <form class="search-inline" method="get" action="busca.php">
                <i class="fas fa-search search-icon" onclick="this.closest('form').submit()" style="cursor:pointer"></i>
                <input
                    type="text"
                    name="busca"
                    placeholder="Buscar notebooks..."
                    aria-label="Buscar"
                    value="<?= htmlspecialchars($termoBusca ?? '') ?>"
                    required>
            </form>

            <!-- LOGIN/LOGOUT -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" id="logoutBtn" class="btn btn-logout">Sair</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-login">Login</a>
            <?php endif; ?>

            <a href="carrinho.php"><button class="btn-carrinho" aria-label="Abrir carrinho"><i class="fas fa-shopping-cart"></i></button></a>
        </nav>
    </header>
    <!-- HERO -->
    <section id="hero" class="hero">
        <video class="hero__video" autoplay muted loop playsinline>
            <source src="videos/2025 ROG Strix SCAR 16⧸18 - Victory, accelerated. ｜ ROG.mp4" type="video/mp4">
        </video>
        <div class="hero__overlay"></div>
        <div class="hero__content container">
            <h1>Explore o futuro dos Notebooks</h1>
            <p>Descubra tecnologia de ponta, desempenho e design elegante.</p>
            <a href="#products" class="btn--primary">Ver Produtos</a>
        </div>
    </section>

    <main>
        <!-- PRODUCTS -->
        <section id="products" class="products container">
            <h2 class="section__title">Destaques</h2>
            <div class="products__grid">
                <?php foreach ($produtos as $p): ?>
                    <article class="card">
                        <img src="uploads/<?= htmlspecialchars($p['imagem'], ENT_QUOTES) ?>" alt="<?= htmlspecialchars($p['nome'], ENT_QUOTES) ?>">
                        <div class="card__info">
                            <h3><?= htmlspecialchars($p['nome'], ENT_QUOTES) ?></h3>
                            <p class="price">R$ <?= number_format($p['preco'], 2, ',', '.') ?></p>
                            <?php if ($p['estoque'] > 0): ?>
                                <a href="detalhes.php?id=<?= $p['id'] ?>"><button class="btn-outline">Detalhes</button></a>
                            <?php else: ?>
                                <button class="btn-outline" disabled>Esgotado</button>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- CATEGORIES -->
        <section id="categories" class="categories container">
            <h2 class="section__title">Categorias</h2>
            <div class="panels-container">
                <a href="categoria-gamer.php" class="panel" style="background-image: url('images/bg-gamer.jpg')">
                    <h3>Gamer</h3>
                </a>
                <a href="categoria-2em1.php" class="panel" style="background-image: url('images/bg2em1.jpg')">
                    <h3>2 em 1</h3>
                </a>
                <a href="categoria-premium.php" class="panel" style="background-image: url('images/webp.jpg')">
                    <h3>Premium</h3>
                </a>
                <a href="categoria-custo-beneficio.php" class="panel" style="background-image: url('images/bg-custobeneficio.jpg')">
                    <h3>Custo-Benefício</h3>
                </a>
                <a href="categoria-estudante.php" class="panel" style="background-image: url('images/bg-estudante.jpg')">
                    <h3>Estudos</h3>
                </a>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer id="footer" class="footer">
        <small>© 2025 Gazer Notebooks. Todos os direitos reservados.</small>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('logoutBtn');
            if (btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Tem certeza?',
                        text: 'Tem certeza que deseja sair?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sim',
                        cancelButtonText: 'Cancelar',
                        focusCancel: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(result => {
                        if (result.isConfirmed) {
                            window.location.href = btn.getAttribute('href');
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>