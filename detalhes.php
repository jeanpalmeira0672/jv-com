<?php
session_start();
require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch();

if (!$produto) {
    echo '<h1>Produto não encontrado!</h1>';
    exit;
}

$parcela = $produto['preco'] / 10;

// Busca produtos gamer para o carrossel, exceto o atual
$stmtGamer = $pdo->prepare(
    "SELECT id, nome, imagem, preco
     FROM produtos
     WHERE categoria = 'gamer' AND id <> ?
     ORDER BY RAND()
     LIMIT 10"
);
$stmtGamer->execute([$id]);
$gamerProdutos = $stmtGamer->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($produto['nome'], ENT_QUOTES) ?> • Detalhes</title>
    <link rel="stylesheet" href="css/detalhes.css">
    <link rel="icon" href="images/logo.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
</head>

<body>
    <header id="header">
        <nav class="nav">
            <ul class="nav__list">
                <li><a href="main.php#hero">Início</a></li>
                <li><a href="main.php#products">Destaques</a></li>
                <li><a href="main.php#categories">Categorias</a></li>
                <li><a href="contato.html">Contato</a></li>
            </ul>
            <div class="search-inline">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Buscar notebooks..." aria-label="Buscar">
            </div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" id="logoutBtn" class="btn btn-logout">Sair</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-login">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="container product-detail">
        <div class="detail-media">
            <?php if (!empty($produto['video'])): ?>
                <div class="video-wrapper">
                    <iframe
                        src="https://www.youtube.com/embed/<?= htmlspecialchars($produto['video'], ENT_QUOTES) ?>"
                        title="Vídeo do produto"
                        frameborder="0"
                        allowfullscreen>
                    </iframe>
                </div>
            <?php endif; ?>

            <div class="description">
                <h2>Descrição</h2>
                <p><?= nl2br(htmlspecialchars($produto['descricao'], ENT_QUOTES)) ?></p>
            </div>
        </div>
        <div class="detail-info">
            <div class="price-cart">
                <span class="detail-price">
                    R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                </span>
                <small class="installment">
                    ou em 10x de R$ <?= number_format($parcela, 2, ',', '.') ?> sem juros
                </small>
                <form method="post" action="carrinho.php">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
                    <button
                        type="submit"
                        class="btn-cart"
                        <?= $produto['estoque'] == 0 ? 'disabled' : '' ?>>
                        <?= $produto['estoque'] == 0 ? 'Sem Estoque' : 'Adicionar ao Carrinho' ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="containerr">
            <small>© 2025 Fé Notebooks</small>
        </div>
        <br>
        <div class="formas__pagamento">
            <img src="images/american-express.svg" alt="">
            <img src="images/elo.svg" alt="">
            <img src="images/hipercard.svg" alt="">
            <img src="images/mastercard.svg" alt="">
            <img src="images/pix.svg" alt="">
            <img src="images/visa.svg" alt="">
        </div>
    </footer>
</body>

</html>