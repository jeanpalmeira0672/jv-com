<?php
session_start();
require_once 'db.php';

// Redireciona se não estiver logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$usuario_id = $_SESSION['user_id'];

// Processa requisições AJAX de update (sem reload)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax'] == '1') {
    $action     = $_POST['action'] ?? '';
    $produto_id = intval($_POST['produto_id']);

    if ($action === 'update') {
        $qty = max(1, intval($_POST['quantity']));
        // Verifica estoque e preço
        $stmt = $pdo->prepare("SELECT estoque, preco FROM produtos WHERE id = ?");
        $stmt->execute([$produto_id]);
        $produto = $stmt->fetch();
        if ($produto && $qty <= $produto['estoque']) {
            // Atualiza quantidade
            $pdo->prepare("UPDATE carrinho SET quantidade = ? WHERE usuario_id = ? AND produto_id = ?")
                ->execute([$qty, $usuario_id, $produto_id]);
            // Recalcula totais
            $stmt = $pdo->prepare("
                SELECT p.preco, c.quantidade
                  FROM carrinho c
                  JOIN produtos p ON c.produto_id = p.id
                 WHERE c.usuario_id = ?
            ");
            $stmt->execute([$usuario_id]);
            $items = $stmt->fetchAll();
            $linhaTotal = $produto['preco'] * $qty;
            $totalGeral = 0;
            foreach ($items as $it) {
                $totalGeral += $it['preco'] * $it['quantidade'];
            }
            header('Content-Type: application/json');
            echo json_encode([
                'linhaTotal' => number_format($linhaTotal, 2, ',', '.'),
                'totalGeral' => number_format($totalGeral, 2, ',', '.')
            ]);
            exit;
        }
    }
    http_response_code(400);
    exit;
}

// Processa ações normais: add, update via form, remove
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['ajax'])) {
    $action     = $_POST['action'] ?? '';
    $produto_id = intval($_POST['produto_id'] ?? 0);

    if ($action === 'add') {
        // Inserir ou incrementar
        $stmt = $pdo->prepare("SELECT quantidade FROM carrinho WHERE usuario_id = ? AND produto_id = ?");
        $stmt->execute([$usuario_id, $produto_id]);
        if ($row = $stmt->fetch()) {
            $novo_qt = $row['quantidade'] + 1;
            $pdo->prepare("UPDATE carrinho SET quantidade = ? WHERE usuario_id = ? AND produto_id = ?")
                ->execute([$novo_qt, $usuario_id, $produto_id]);
        } else {
            $pdo->prepare("INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES (?, ?, 1)")
                ->execute([$usuario_id, $produto_id]);
        }
    } elseif ($action === 'update') {
        // Fallback update via form
        $qty = max(1, intval($_POST['quantity']));
        $stmt = $pdo->prepare("SELECT estoque FROM produtos WHERE id = ?");
        $stmt->execute([$produto_id]);
        $produto = $stmt->fetch();
        if ($produto && $qty <= $produto['estoque']) {
            $pdo->prepare("UPDATE carrinho SET quantidade = ? WHERE usuario_id = ? AND produto_id = ?")
                ->execute([$qty, $usuario_id, $produto_id]);
        }
    } elseif ($action === 'remove') {
        // Remove item
        $pdo->prepare("DELETE FROM carrinho WHERE usuario_id = ? AND produto_id = ?")
            ->execute([$usuario_id, $produto_id]);
    }

    header('Location: carrinho.php');
    exit;
}

// Busca itens do carrinho
$stmt = $pdo->prepare("
    SELECT c.produto_id, c.quantidade, p.nome, p.preco, p.imagem, p.estoque
      FROM carrinho c
      JOIN produtos p ON c.produto_id = p.id
     WHERE c.usuario_id = ?
");
$stmt->execute([$usuario_id]);
$items = $stmt->fetchAll();

// Calcula total inicial
$total = 0;
foreach ($items as $it) {
    $total += $it['preco'] * $it['quantidade'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>FéNotebooks.com</title>
    <link rel="stylesheet" href="css/carrinho.css">
    <link rel="icon" href="images/logo.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" defer></script>
</head>

<body>
    <header id="header">
        <nav class="nav">
            <ul class="nav__list">
                <li><a href="main.php#hero">Início</a></li>
                <li><a href="main.php#products">Destaques</a></li>
                <li><a href="main.php#categories">Categorias</a></li>
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

    <main class="container">
        <h1>Meu Carrinho</h1>

        <?php if (empty($items)): ?>
            <p class="empty">
                Seu carrinho está vazio.
                <a href="main.php#products">Veja nossos produtos</a>
            </p>
        <?php else: ?>
            <div class="cart-resumo-container">
                <div class="cart-wrapper">
                    <table class="cart" aria-label="Lista de produtos no carrinho">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Unitário</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr data-id="<?= $item['produto_id'] ?>">
                                    <td class="col-produto">
                                        <img src="<?= htmlspecialchars($item['imagem']) ?>" class="product-image" alt="">
                                        <span><?= htmlspecialchars($item['nome']) ?></span>
                                        <?php if ($item['estoque'] == 0): ?>
                                            <div class="out-of-stock">Sem estoque</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="col-qty">
                                        <button class="btn-decrease" <?= $item['quantidade'] <= 1 ? 'disabled' : '' ?>>−</button>
                                        <input
                                            type="number"
                                            class="qty-input"
                                            min="1"
                                            max="<?= $item['estoque'] ?>"
                                            value="<?= $item['quantidade'] ?>">
                                        <button class="btn-increase" <?= $item['quantidade'] >= $item['estoque'] ? 'disabled' : '' ?>>+</button>
                                        <form method="post" class="fallback-update" style="display:none">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="produto_id" value="<?= $item['produto_id'] ?>">
                                            <input type="hidden" name="quantity" value="<?= $item['quantidade'] ?>">
                                        </form>
                                    </td>
                                    <td class="col-unit">R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                                    <td class="col-line">R$ <span class="line-total"><?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></span></td>
                                    <td class="col-remove">
                                        <form method="post">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="produto_id" value="<?= $item['produto_id'] ?>">
                                            <button class="btn-remove">&times;</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <aside class="order-summary" aria-label="Resumo do pedido">
                    <h2>Resumo do Pedido</h2>
                    <div>Total:<span id="total-geral">R$ <?= number_format($total, 2, ',', '.') ?></span></div>
                    <button class="btn-primary" type="button">Finalizar Compra</button>
                </aside>
            </div>
        <?php endif; ?>
    </main>
    <script src="main.js"></script>

    <script>
        document.querySelectorAll('tr[data-id]').forEach(tr => {
            const id = tr.dataset.id;
            const btnInc = tr.querySelector('.btn-increase');
            const btnDec = tr.querySelector('.btn-decrease');
            const input = tr.querySelector('.qty-input');
            const lineTotalEl = tr.querySelector('.line-total');
            const totalGeralEl = document.getElementById('total-geral');

            function sendUpdate(qty) {
                fetch('carrinho.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            ajax: 1,
                            action: 'update',
                            produto_id: id,
                            quantity: qty
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        lineTotalEl.textContent = data.linhaTotal;
                        totalGeralEl.textContent = 'R$ ' + data.totalGeral;
                        input.value = qty;
                        btnDec.disabled = qty <= 1;
                        btnInc.disabled = qty >= parseInt(input.max);
                    })
                    .catch(() => {
                        const form = tr.querySelector('.fallback-update');
                        form.quantity.value = input.value;
                        form.submit();
                    });
            }

            btnInc.addEventListener('click', () => {
                const nv = Math.min(parseInt(input.max), parseInt(input.value) + 1);
                sendUpdate(nv);
            });
            btnDec.addEventListener('click', () => {
                const nv = Math.max(1, parseInt(input.value) - 1);
                sendUpdate(nv);
            });
        });
    </script>
</body>

</html>