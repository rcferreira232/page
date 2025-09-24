<?php
session_start();

// Verificar se está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/classes/Pigeon.php';

$pigeon = new Pigeon();
$message = '';
$messageType = '';

// Processar formulário
if ($_POST) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $name = trim($_POST['name'] ?? '');
        $breed = trim($_POST['breed'] ?? '');
        $color = trim($_POST['color'] ?? '');
        $age = intval($_POST['age'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $image_url = trim($_POST['image_url'] ?? '');
        
        if (!empty($name)) {
            if ($pigeon->create($name, $breed, $color, $age, $description, $image_url, $_SESSION['user_id'])) {
                $message = 'Pombo cadastrado com sucesso!';
                $messageType = 'success';
            } else {
                $message = 'Erro ao cadastrar pombo.';
                $messageType = 'error';
            }
        } else {
            $message = 'Nome é obrigatório.';
            $messageType = 'error';
        }
    }
    
    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($pigeon->delete($id, $_SESSION['user_id'])) {
            $message = 'Pombo removido com sucesso!';
            $messageType = 'success';
        } else {
            $message = 'Erro ao remover pombo.';
            $messageType = 'error';
        }
    }
}

// Buscar pombos do usuário
$userPigeons = $pigeon->getAllByUser($_SESSION['user_id']);
$allPigeons = $pigeon->getAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pombo Retailing United</title>
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .dashboard {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .welcome {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        .section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .form-group input, .form-group textarea {
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #545b62;
        }
        .pigeon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .pigeon-card {
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background: #f8f9fa;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .pigeon-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .pigeon-card h3 {
            color: #007bff;
            margin-bottom: 10px;
        }
        .pigeon-info {
            margin-bottom: 15px;
        }
        .pigeon-info strong {
            color: #333;
        }
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #dc3545;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-btn:hover {
            background: #c82333;
        }
        .tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }
        .tab {
            padding: 15px 25px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #666;
            border-bottom: 2px solid transparent;
        }
        .tab.active {
            color: #007bff;
            border-bottom-color: #007bff;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
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
            </div>
            <div class="nav-links">
                <a href="./index.php" class="nav-link">home</a>
                <a href="dashboard.php" class="nav-link">dashboard</a>
                <a href="logout.php" class="nav-link">logout</a>
            </div>
        </div>
    </nav>

    <main class="page">
        <div class="dashboard">
            <div class="welcome">
                <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p>Gerencie seus pombos aqui</p>
            </div>

            <?php if ($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="tabs">
                <button class="tab active" onclick="switchTab('add')">Cadastrar Pombo</button>
                <button class="tab" onclick="switchTab('my')">Meus Pombos (<?php echo count($userPigeons); ?>)</button>
                <button class="tab" onclick="switchTab('all')">Todos os Pombos (<?php echo count($allPigeons); ?>)</button>
            </div>

            <!-- Cadastrar Pombo -->
            <div id="add-content" class="tab-content active">
                <div class="section">
                    <h2>Cadastrar Novo Pombo</h2>
                    <form method="POST">
                        <input type="hidden" name="action" value="add">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name">Nome*:</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="breed">Raça:</label>
                                <input type="text" id="breed" name="breed">
                            </div>
                            <div class="form-group">
                                <label for="color">Cor:</label>
                                <input type="text" id="color" name="color">
                            </div>
                            <div class="form-group">
                                <label for="age">Idade (meses):</label>
                                <input type="number" id="age" name="age" min="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image_url">Link da Imagem:</label>
                            <input type="url" id="image_url" name="image_url" placeholder="https://exemplo.com/imagem.jpg">
                        </div>
                        <div class="form-group">
                            <label for="description">Descrição:</label>
                            <textarea id="description" name="description" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Cadastrar Pombo
                        </button>
                    </form>
                </div>
            </div>

            <!-- Meus Pombos -->
            <div id="my-content" class="tab-content">
                <div class="section">
                    <h2>Meus Pombos</h2>
                    <?php if (empty($userPigeons)): ?>
                        <p>Você ainda não cadastrou nenhum pombo.</p>
                    <?php else: ?>
                        <div class="pigeon-grid">
                            <?php foreach ($userPigeons as $p): ?>
                                <div class="pigeon-card">
                                    <?php if ($p['image_url']): ?>
                                        <div class="pigeon-image" style="margin-bottom: 15px;">
                                            <img src="<?php echo htmlspecialchars($p['image_url']); ?>" 
                                                 alt="<?php echo htmlspecialchars($p['name']); ?>" 
                                                 style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;"
                                                 onerror="this.style.display='none'">
                                        </div>
                                    <?php endif; ?>
                                    <h3><i class="fas fa-dove"></i> <?php echo htmlspecialchars($p['name']); ?></h3>
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
                                        <p><strong>Cadastrado:</strong> <?php echo date('d/m/Y H:i', strtotime($p['created_at'])); ?></p>
                                    </div>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja remover este pombo?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Remover
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Todos os Pombos -->
            <div id="all-content" class="tab-content">
                <div class="section">
                    <h2>Todos os Pombos</h2>
                    <?php if (empty($allPigeons)): ?>
                        <p>Nenhum pombo cadastrado ainda.</p>
                    <?php else: ?>
                        <div class="pigeon-grid">
                            <?php foreach ($allPigeons as $p): ?>
                                <div class="pigeon-card">
                                    <?php if ($p['image_url']): ?>
                                        <div class="pigeon-image" style="margin-bottom: 15px;">
                                            <img src="<?php echo htmlspecialchars($p['image_url']); ?>" 
                                                 alt="<?php echo htmlspecialchars($p['name']); ?>" 
                                                 style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;"
                                                 onerror="this.style.display='none'">
                                        </div>
                                    <?php endif; ?>
                                    <h3><i class="fas fa-dove"></i> <?php echo htmlspecialchars($p['name']); ?></h3>
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
                                        <p><strong>Dono:</strong> <?php echo htmlspecialchars($p['username'] ?? 'N/A'); ?></p>
                                        <p><strong>Cadastrado:</strong> <?php echo date('d/m/Y H:i', strtotime($p['created_at'])); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        function switchTab(tabName) {
            // Remove active class from all tabs and content
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            event.target.classList.add('active');
            document.getElementById(tabName + '-content').classList.add('active');
        }
    </script>
</body>
</html>
