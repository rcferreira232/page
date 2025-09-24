<?php
session_start();
require_once __DIR__ . '/classes/Pigeon.php';

$pigeon = new Pigeon();
$allPigeons = $pigeon->getAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pombos - Pombo Retailing United</title>
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .pigeons-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .header-section {
            text-align: center;
            margin-bottom: 50px;
        }
        .header-section h1 {
            color: #007bff;
            margin-bottom: 20px;
        }
        .pigeon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        .pigeon-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            border: 2px solid #f0f0f0;
        }
        .pigeon-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-color: #007bff;
        }
        .pigeon-card h3 {
            color: #007bff;
            margin-bottom: 15px;
            font-size: 1.5em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .pigeon-info p {
            margin-bottom: 8px;
            color: #666;
        }
        .pigeon-info strong {
            color: #333;
        }
        .owner-badge {
            background: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            margin-top: 10px;
            display: inline-block;
        }
        .no-pigeons {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .no-pigeons i {
            font-size: 3em;
            color: #ddd;
            margin-bottom: 20px;
        }
        .stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        .stat-card {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            min-width: 150px;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
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

    <main class="page">
        <div class="pigeons-container">
            <div class="header-section">
                <h1><i class="fas fa-dove"></i> Nossos Pombos</h1>
                <p>Conheça todos os pombos cadastrados em nossa plataforma</p>
            </div>

            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($allPigeons); ?></div>
                    <div>Pombos Registrados</div>
                </div>
                <?php 
                $breeds = array_unique(array_filter(array_column($allPigeons, 'breed')));
                $colors = array_unique(array_filter(array_column($allPigeons, 'color')));
                ?>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($breeds); ?></div>
                    <div>Raças Diferentes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($colors); ?></div>
                    <div>Cores Diferentes</div>
                </div>
            </div>

            <?php if (empty($allPigeons)): ?>
                <div class="no-pigeons">
                    <i class="fas fa-dove"></i>
                    <h3>Nenhum pombo cadastrado ainda</h3>
                    <p>Seja o primeiro a cadastrar um pombo!</p>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="login.php" class="btn" style="display: inline-block; margin-top: 20px; padding: 15px 30px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;">Fazer Login</a>
                    <?php else: ?>
                        <a href="dashboard.php" class="btn" style="display: inline-block; margin-top: 20px; padding: 15px 30px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;">Cadastrar Pombo</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="pigeon-grid">
                    <?php foreach ($allPigeons as $p): ?>
                        <div class="pigeon-card">
                            <?php if ($p['image_url']): ?>
                                <div class="pigeon-image" style="margin-bottom: 15px;">
                                    <img src="<?php echo htmlspecialchars($p['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($p['name']); ?>" 
                                         style="width: 100%; height: 250px; object-fit: cover; border-radius: 10px;"
                                         onerror="this.style.display='none'">
                                </div>
                            <?php endif; ?>
                            <h3>
                                <i class="fas fa-dove"></i>
                                <?php echo htmlspecialchars($p['name']); ?>
                            </h3>
                            <div class="pigeon-info">
                                <?php if ($p['breed']): ?>
                                    <p><strong>Raça:</strong> <?php echo htmlspecialchars($p['breed']); ?></p>
                                <?php endif; ?>
                                <?php if ($p['color']): ?>
                                    <p><strong>Cor:</strong> <?php echo htmlspecialchars($p['color']); ?></p>
                                <?php endif; ?>
                                <?php if ($p['age']): ?>
                                    <p><strong>Idade:</strong> <?php echo htmlspecialchars($p['age']); ?> meses</p>
                                <?php endif; ?>
                                <?php if ($p['description']): ?>
                                    <p><strong>Descrição:</strong> <?php echo htmlspecialchars($p['description']); ?></p>
                                <?php endif; ?>
                                <p><strong>Cadastrado em:</strong> <?php echo date('d/m/Y', strtotime($p['created_at'])); ?></p>
                            </div>
                            <div class="owner-badge">
                                <i class="fas fa-user"></i>
                                <?php echo htmlspecialchars($p['username'] ?? 'Anônimo'); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="./js/app.js"></script>
</body>
</html>
