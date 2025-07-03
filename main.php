<?php
require_once 'db.php'; // conexão PDO

$termoBusca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

if ($termoBusca !== '') {
    $sql = "SELECT id, nome, preco, imagem, estoque FROM produtos WHERE nome LIKE :busca";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':busca', '%' . $termoBusca . '%');
} else {
    $stmt = $pdo->prepare("SELECT id, nome, preco, imagem, estoque FROM produtos");
}

$stmt->execute();
$produtos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gazer Notebooks</title>
    <link rel="icon" href="images/logoprojetojv1.png">
    <link rel="stylesheet" href="css/style2.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- NAVBAR -->
    <header id="header">
        <nav class="nav">
            <ul class="nav__list">
                <li><a href="#hero">Início</a></li>
                <li><a href="#products">Produtos</a></li>
                <li><a href="#categories">Categorias</a></li>
                <li><a href="contato.html">Contato</a></li>
            </ul>

            <!-- FORMULÁRIO DE BUSCA -->
            <form class="search-inline" method="get" action="busca.php">
                <input
                    type="text"
                    name="busca"
                    placeholder="Buscar notebooks..."
                    aria-label="Buscar"
                    value="<?= htmlspecialchars($termoBusca) ?>"
                    required
                >
                <button type="submit" aria-label="Buscar">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <a href="login.php" class="btn btn-login">Login</a>
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
    <?php if ($termoBusca !== ''): ?>
        <h2 class="section__title">Resultados para: "<?= htmlspecialchars($termoBusca) ?>"</h2>
    <?php else: ?>
        <h2 class="section__title">Destaques</h2>
    <?php endif; ?>

    <?php if (count($produtos) === 0): ?>
        <div class="no-results">
            <h3>Nenhum produto encontrado para: "<?= htmlspecialchars($termoBusca) ?>"</h3>
            <a href="main.php" class="btn-outline">Voltar para todos os produtos</a>
        </div>
    <?php else: ?>
        <div class="products__grid">
            <?php foreach ($produtos as $p): ?>
                <article class="card">
                    <img
                        src="uploads/<?= htmlspecialchars($p['imagem']) ?>"
                        alt="<?= htmlspecialchars($p['nome']) ?>">
                    <div class="card__info">
                        <h3><?= htmlspecialchars($p['nome']) ?></h3>
                        <p class="price">R$ <?= number_format($p['preco'], 2, ',', '.') ?></p>
                        <?php if ($p['estoque'] > 0): ?>
                            <button class="btn-outline">Detalhes</button>
                        <?php else: ?>
                            <button class="btn-outline" disabled>Esgotado</button>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
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
                <a href="categoria-custo-beneficio.php" class="panel" style="background-image: url('https://images.unsplash.com/photo-1551009175-8a68da93d5f9')">
                    <h3>Custo-Benefício</h3>
                </a>
                <a href="categoria-estudante.php" class="panel" style="background-image: url('https://images.unsplash.com/photo-1549880338-65ddcdfd017b')">
                    <h3>Estudante</h3>
                </a>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer id="footer" class="footer">
        <small>© 2025 Gazer Notebooks. Todos os direitos reservados.</small>
    </footer>

</body>
</html>