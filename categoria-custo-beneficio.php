<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gazer Notebooks - Categoria Gamer</title>
    <link rel="icon" href="images/logoprojetojv1.png">
    <link rel="stylesheet" href="css/categoria.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- NAVBAR FIXA E FUNCIONAL -->
    <header id="header">
        <nav class="nav">
            <ul class="nav__list">
                <li><a href="main.php">Início</a></li>
                <li><a href="index.html#products">Produtos</a></li>
                <li><a href="main.php#categories">Categorias</a></li>
                <li><a href="contato.html">Contato</a></li>
            </ul>
            <div class="search-inline">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Buscar notebooks..." aria-label="Buscar">
            </div>
            <a href="login.php" class="btn btn-login">Login</a>
        </nav>
    </header>

    <section class="category-hero" style="background-image:url('images/bg-gamer.jpg')">
        <div class="category-hero__overlay"></div>
        <div class="container category-hero__content">
            <h1>Gaming</h1>
            <p>Potência e performance para suas jogadas</p>
        </div>
    </section>

    <main class="container layout--with-sidebar">

        <section class="products">
            <h2 style="text-align: center;">Notebooks Gamer</h2>
            <div class="products__grid">
                <article class="card">
                    <img src="images/299AF6C6-7DD0-4322-9343-3E37828784F1.png" alt="ROG Strix SCAR">
                    <div class="card__info">
                        <h3>ROG Strix SCAR 18</h3>
                        <p class="price">R$ 49.499,10</p>
                        <button class="btn btn-outline">Detalhes</button>
                    </div>
                </article>
                <!-- Replicar cards -->
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <small>© 2025 Gazer Notebooks</small>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>

</html>