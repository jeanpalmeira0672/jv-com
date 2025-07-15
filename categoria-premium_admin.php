<?php
session_start();
require_once 'db.php'; // conexão PDO

// caminho base para as imagens dos notebooks
$imgBase = 'uploads/';

// Parâmetros de filtro e paginação
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 0;
$page      = isset($_GET['page'])      ? max(1, intval($_GET['page']))    : 1;
$perPage   = 18;
$offset    = ($page - 1) * $perPage;

// Construir condição WHERE
$where  = ["categoria = 'premium'"];
$params = [];

if ($min_price > 0) {
    $where[]  = "preco >= ?";
    $params[] = $min_price;
}
if ($max_price > 0) {
    $where[]  = "preco <= ?";
    $params[] = $max_price;
}

$where_sql = 'WHERE ' . implode(' AND ', $where);

// Contar total de itens para paginação
$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM produtos $where_sql");
$totalStmt->execute($params);
$total       = (int)$totalStmt->fetchColumn();
$totalPages  = (int)ceil($total / $perPage);

// Consulta principal com limite e offset
$sql   = "SELECT * FROM produtos $where_sql LIMIT ? OFFSET ?";
$stmt  = $pdo->prepare($sql);
$params[] = $perPage;
$params[] = $offset;
$stmt->execute($params);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FéNotebooks.com</title>
    <link rel="icon" href="images/logo.png">
    <link rel="stylesheet" href="css/categoria.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <header id="header">
        <nav class="nav">
            <ul class="nav__list">
                <li><a href="main.php#hero">Início</a></li>
                <li><a href="main.php#products">Destaques</a></li>
                <li><a href="main.php#categories">Categorias</a></li>
                <li><a href="produtos.php">CRUD</a></li>
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

            <!-- LOGIN/LOGOUT ORIGINAL -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" id="logoutBtn" class="btn btn-logout">Sair</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-login">Login</a>
            <?php endif; ?>

            <a href="carrinho.php"><button class="btn-carrinho" aria-label="Abrir carrinho"><i class="fas fa-shopping-cart"></i></button></a>
        </nav>
    </header>

    <section class="category-hero" style="background-image:url('images/webp.jpg')">
        <div class="category-hero__overlay"></div>
        <div class="container category-hero__content">
            <h1>Premium</h1>
            <p>Sofisticação e alto desempenho para quem exige o melhor.</p>
        </div>
    </section>

    <main class="container">

        <!-- PRODUTOS -->
        <section id="products" class="products">
            <h2 class="section__title">Notebooks Premium</h2>
            <div class="layout--with-sidebar">
                <!-- FILTRO (à esquerda) -->
                <aside class="sidebar">
                    <form method="get" class="filter">
                        <div class="filter__group">
                            <label style="text-align: center;">Intervalo de Preço</label>

                            <div class="range-slider">
                                <div class="slider-track"></div>
                                <input type="range" id="rangeMin" min="0" max="15999" step="1" value="<?= $_GET['min_price'] ?? 0 ?>">
                                <input type="range" id="rangeMax" min="0" max="15999" step="1" value="<?= $_GET['max_price'] ?? 15999 ?>">
                            </div>

                            <div class="price-inputs">
                                <div>
                                    <label for="min_price">Mínimo</label>
                                    <input type="number" name="min_price" id="min_price" value="<?= $_GET['min_price'] ?? 0 ?>">
                                </div>
                                <span style="margin: 0 .5rem; padding-top: 18px;">–</span>
                                <div>
                                    <label for="max_price">Máximo</label>
                                    <input type="number" name="max_price" id="max_price" value="<?= $_GET['max_price'] ?? 15999 ?>">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-apply">Filtrar</button>
                    </form>


                </aside>
                <div class="products__grid">
                    <?php foreach ($produtos as $p): ?>
                        <article class="card">
                            <img src="<?= htmlspecialchars($p['imagem'], ENT_QUOTES) ?>"
                                alt="<?= htmlspecialchars($p['nome'], ENT_QUOTES) ?>">
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
        </div>

        <!-- PAGINAÇÃO (com espaçamento reduzido) -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>&min_price=<?= htmlspecialchars($_GET['min_price'] ?? '') ?>
                          &max_price=<?= htmlspecialchars($_GET['max_price'] ?? '') ?>"
                    class="<?= ($i == $page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </main>

    <footer class="footer">
        <div class="container"><small>© 2025 Fé Notebooks</small></div>
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
    <script>
        const rangeMin = document.getElementById("rangeMin");
        const rangeMax = document.getElementById("rangeMax");
        const inputMin = document.getElementById("min_price");
        const inputMax = document.getElementById("max_price");
        const track = document.querySelector(".slider-track");

        const minGap = 100;
        const maxRange = 15999;

        function updateTrackPosition() {
            const min = parseInt(rangeMin.value);
            const max = parseInt(rangeMax.value);

            const percentMin = (min / maxRange) * 100;
            const percentMax = (max / maxRange) * 100;

            track.style.left = percentMin + "%";
            track.style.width = (percentMax - percentMin) + "%";
        }

        function syncSliders() {
            let minVal = parseInt(rangeMin.value);
            let maxVal = parseInt(rangeMax.value);

            if (maxVal - minVal <= minGap) {
                if (event.target === rangeMin) {
                    rangeMin.value = maxVal - minGap;
                } else {
                    rangeMax.value = minVal + minGap;
                }
            }

            inputMin.value = rangeMin.value;
            inputMax.value = rangeMax.value;
            updateTrackPosition();
        }

        function syncInputs() {
            let minVal = parseInt(inputMin.value);
            let maxVal = parseInt(inputMax.value);

            if (maxVal - minVal >= minGap && maxVal <= maxRange) {
                rangeMin.value = minVal;
                rangeMax.value = maxVal;
                updateTrackPosition();
            }
        }

        rangeMin.addEventListener("input", syncSliders);
        rangeMax.addEventListener("input", syncSliders);
        inputMin.addEventListener("change", syncInputs);
        inputMax.addEventListener("change", syncInputs);

        updateTrackPosition(); // inicializa ao carregar
    </script>

</body>

</html>