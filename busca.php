<?php
require_once 'db.php';

$termoBusca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

if ($termoBusca !== '') {
    $sql = "SELECT id, nome, preco, imagem, estoque FROM produtos WHERE nome LIKE :busca";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':busca', '%' . $termoBusca . '%');
    $stmt->execute();
    $produtos = $stmt->fetchAll();
} else {
    $produtos = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Resultados da Busca</title>
    <link rel="stylesheet" href="css/style2.css" />
</head>
<body>
    <header>
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

    <main class="container" style="padding-top: 100px;">
    <h1>Resultados para: "<?= htmlspecialchars($termoBusca) ?>"</h1>
        <!-- Botão Voltar com seta (⬅️) alinhado à esquerda -->
        <div style="text-align: left; margin-top: 1rem; margin-left: 2rem; margin-bottom: 2rem;">
            <a href="main.php" class="btn--primary">⬅️ Voltar</a>
        </div>

        <?php if (count($produtos) === 0): ?>
            <p class="no-results">Nenhum produto encontrado.</p>
        <?php else: ?>
            <div class="products__grid">
                <?php foreach ($produtos as $p): ?>
                    <article class="card">
                        <img src="uploads/<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>" />
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
    </main>
    <footer id="footer" class="footer">
        <small>© 2025 Gazer Notebooks. Todos os direitos reservados.</small>
    </footer>

</body>
</html>
