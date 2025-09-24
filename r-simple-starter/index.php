<?php
session_start();
require_once __DIR__ . '/classes/Pigeon.php';

// Simple PHP site converted from HTML
// Page: Home/Index

// Buscar pombos mais recentes para exibir na home
$pigeon = new Pigeon();
$recentPigeons = $pigeon->getAll();
// Limitar a 6 pombos mais recentes
$recentPigeons = array_slice($recentPigeons, 0, 6);

// Obter cores únicas para os tags
$allPigeons = $pigeon->getAll();
$uniqueColors = array_unique(array_filter(array_column($allPigeons, 'color')));
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pombo Retailing United</title>
    <!-- favicon -->
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <!-- normalize -->
    <link rel="stylesheet" href="./css/normalize.css">
    <!-- font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <!-- main css -->
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .pigeon {
            position: relative;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .pigeon:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .pigeon-desc {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }
        .pigeon small {
            display: block;
            margin-top: 8px;
            color: #007bff;
            font-weight: bold;
        }
        .tags-list a {
            text-transform: capitalize;
        }
        .no-pigeons-home i {
            display: block;
        }
    </style>
</head>
<body>
    <!-- nav -->
    <nav class="navbar">
        <div class="nav-center">
            <div class="nav-header">
                <a href="./index.php" class="nav-logo">
                    <img src="./img/pombo_logo.svg" alt="pombo retailing united">
                </a>
                <button type="button" class="btn nav-btn">
                    <i class="fas fa-align-justify"></i>
                </button>
            </div>
            <div class="nav-links">
                <a href="./index.php" class="nav-link">home</a>
                <a href="./about.php" class="nav-link">about</a>
                <a href="./pombos.php" class="nav-link">pombos</a>
                <a href="./tags.php" class="nav-link">tags</a>
                <a href="./base.php" class="nav-link">base</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="./dashboard.php" class="nav-link">dashboard</a>
                    <a href="./logout.php" class="nav-link">logout</a>
                <?php else: ?>
                    <a href="./login.php" class="nav-link">login</a>
                <?php endif; ?>
                <div class="nav-link contact-btn">
                    <a href="./contact.php" class="btn">contact</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- end of nav -->
    <main class="page">
        <header class="hero">
            <div class="hero-container">
                <div class="hero-text">
                    <h1>
                        pru
                    </h1>
                    <h4>
                        pru pru
                    </h4>
                </div>
            </div>
        </header>
        <!-- pigeon container -->
        <section class="pigeon-container">
            <div class="tags-container">
                <h4>Cores</h4>
                <div class="tags-list">
                    <?php if (!empty($uniqueColors)): ?>
                        <?php foreach (array_slice($uniqueColors, 0, 6) as $color): ?>
                            <a href="./pombos.php"><?php echo htmlspecialchars($color); ?></a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <a href="./pombos.php">Ver todos</a>
                    <?php endif; ?>
                    <a href="./pombos.php" style="background: #007bff; color: white;">Ver Todos</a>
                </div>
            </div>
            <div class="pigeons-list">
                <?php if (!empty($recentPigeons)): ?>
                    <?php foreach ($recentPigeons as $p): ?>
                        <div class="pigeon">
                            <?php if ($p['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($p['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($p['name']); ?>" 
                                     class="img pigeon-img"
                                     onerror="this.src='./img/pru.jpg'">
                            <?php else: ?>
                                <img src="./img/pru.jpg" alt="pigeon" class="img pigeon-img">
                            <?php endif; ?>
                            <h5><?php echo htmlspecialchars($p['color'] ?: 'Sem cor'); ?></h5>
                            <p>
                                <?php if ($p['breed']): ?>
                                    <?php echo htmlspecialchars($p['breed']); ?> | 
                                <?php endif; ?>
                                <strong><?php echo htmlspecialchars($p['name']); ?></strong>
                                <?php if ($p['age']): ?>
                                    | <?php echo $p['age']; ?> meses
                                <?php endif; ?>
                            </p>
                            <?php if ($p['description']): ?>
                                <p class="pigeon-desc"><?php echo htmlspecialchars(substr($p['description'], 0, 100)); ?><?php echo strlen($p['description']) > 100 ? '...' : ''; ?></p>
                            <?php endif; ?>
                            <small>Por: <?php echo htmlspecialchars($p['username'] ?? 'Anônimo'); ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-pigeons-home" style="text-align: center; padding: 40px; grid-column: 1/-1;">
                        <i class="fas fa-dove" style="font-size: 3em; color: #ddd; margin-bottom: 20px;"></i>
                        <h3>Nenhum pombo cadastrado ainda</h3>
                        <p>Seja o primeiro a cadastrar um pombo!</p>
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="login.php" class="btn" style="display: inline-block; margin-top: 15px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;">Fazer Login</a>
                        <?php else: ?>
                            <a href="dashboard.php" class="btn" style="display: inline-block; margin-top: 15px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;">Cadastrar Pombo</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <!-- end of pigeon container -->
    </main>
    <!-- footer -->
    <footer class="page-footer">
        <p>
            &copy;
            <span id="date"></span>
            <span class="footer-logo">Pombo Retailing United</span>
            Built by 
            <a href="https://github.com/rcferreira232/page" target="_blank" rel="noopener noreferrer">rcferreira232</a> 
        </p>
    </footer>
    <script src="./js/app.js"></script>
</body>
</html>