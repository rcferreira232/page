<?php
session_start();
require_once __DIR__ . '/classes/Pigeon.php';
require_once __DIR__ . '/classes/Cart.php';

$pigeonId = intval($_GET['id'] ?? 0);

if ($pigeonId <= 0) {
    header('Location: index.php');
    exit();
}

$pigeon = new Pigeon();
$pigeonData = $pigeon->getById($pigeonId);

if (!$pigeonData) {
    header('Location: index.php');
    exit();
}

// Buscar outros pombos do mesmo dono
$otherPigeons = $pigeon->getAllByUser($pigeonData['user_id']);
$otherPigeons = array_filter($otherPigeons, function($p) use ($pigeonId) {
    return $p['id'] != $pigeonId;
});
$otherPigeons = array_slice($otherPigeons, 0, 3); // Máximo 3 outros pombos

$cart = new Cart();
$price = rand(15, 150); // Preço simulado baseado no ID
srand($pigeonId); // Para manter o mesmo preço sempre
$price = rand(15, 150);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pigeonData['name']); ?> - Pombo Retailing United</title>
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .pigeon-detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .pigeon-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 50px;
        }
        
        .pigeon-image-section {
            position: relative;
        }
        
        .pigeon-main-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .image-placeholder {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #6c757d;
        }
        
        .image-placeholder i {
            font-size: 4em;
            margin-bottom: 20px;
        }
        
        .pigeon-info-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .pigeon-title {
            font-size: 2.5em;
            color: #007bff;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .pigeon-price {
            font-size: 2em;
            color: #28a745;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .pigeon-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .detail-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s;
        }
        
        .detail-card:hover {
            transform: translateY(-5px);
        }
        
        .detail-card i {
            font-size: 2em;
            color: #007bff;
            margin-bottom: 10px;
        }
        
        .detail-card strong {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        
        .pigeon-description {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .pigeon-description h3 {
            color: #007bff;
            margin-bottom: 15px;
        }
        
        .pigeon-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .btn-large {
            padding: 15px 30px;
            font-size: 1.1em;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex: 1;
            min-width: 200px;
            justify-content: center;
        }
        
        .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-large:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .owner-info {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .owner-info h3 {
            margin-bottom: 15px;
        }
        
        .other-pigeons {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .other-pigeons h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 30px;
        }
        
        .other-pigeons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .mini-pigeon-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s;
            cursor: pointer;
        }
        
        .mini-pigeon-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .mini-pigeon-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto 15px;
        }
        
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .quantity-input {
            width: 60px;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            text-align: center;
            font-size: 1.1em;
        }
        
        .breadcrumb {
            margin-bottom: 30px;
            color: #6c757d;
        }
        
        .breadcrumb a {
            color: #007bff;
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .pigeon-detail {
                grid-template-columns: 1fr;
                gap: 30px;
                padding: 20px;
            }
            
            .pigeon-title {
                font-size: 2em;
            }
            
            .btn-large {
                min-width: auto;
            }
        }
        
        /* Toast notification styles */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            z-index: 1000;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s;
        }
        
        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }
        
        .toast.error {
            background: #dc3545;
        }
        
        .cart-count {
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.8em;
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 18px;
            text-align: center;
            line-height: 1.2;
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
                <a href="./pombos.php" class="nav-link">pombos</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="./dashboard.php" class="nav-link">dashboard</a>
                    <a href="./logout.php" class="nav-link">logout</a>
                <?php else: ?>
                    <a href="./login.php" class="nav-link">login</a>
                <?php endif; ?>
                <div class="nav-link cart-btn" style="position: relative;">
                    <a href="./cart.php" class="btn" id="cart-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="cart-count" class="cart-count"><?php echo $cart->getItemCount(); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="page">
        <div class="pigeon-detail-container">
            <div class="breadcrumb">
                <a href="index.php">Home</a> > <a href="pombos.php">Pombos</a> > <?php echo htmlspecialchars($pigeonData['name']); ?>
            </div>

            <div class="pigeon-detail">
                <div class="pigeon-image-section">
                    <?php if ($pigeonData['image_url']): ?>
                        <img src="<?php echo htmlspecialchars($pigeonData['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($pigeonData['name']); ?>" 
                             class="pigeon-main-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="image-placeholder" style="display: none;">
                            <i class="fas fa-dove"></i>
                            <h3>Sem imagem disponível</h3>
                        </div>
                    <?php else: ?>
                        <div class="image-placeholder">
                            <i class="fas fa-dove"></i>
                            <h3>Sem imagem disponível</h3>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="pigeon-info-section">
                    <h1 class="pigeon-title">
                        <i class="fas fa-dove"></i>
                        <?php echo htmlspecialchars($pigeonData['name']); ?>
                    </h1>

                    <div class="pigeon-price">
                        R$ <?php echo number_format($price, 2, ',', '.'); ?>
                    </div>

                    <div class="pigeon-details">
                        <?php if ($pigeonData['breed']): ?>
                            <div class="detail-card">
                                <i class="fas fa-dna"></i>
                                <strong>Raça</strong>
                                <span><?php echo htmlspecialchars($pigeonData['breed']); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($pigeonData['color']): ?>
                            <div class="detail-card">
                                <i class="fas fa-palette"></i>
                                <strong>Cor</strong>
                                <span><?php echo htmlspecialchars($pigeonData['color']); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($pigeonData['age']): ?>
                            <div class="detail-card">
                                <i class="fas fa-calendar-alt"></i>
                                <strong>Idade</strong>
                                <span><?php echo $pigeonData['age']; ?> meses</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="detail-card">
                            <i class="fas fa-clock"></i>
                            <strong>Cadastrado</strong>
                            <span><?php echo date('d/m/Y', strtotime($pigeonData['created_at'])); ?></span>
                        </div>
                    </div>

                    <?php if ($pigeonData['description']): ?>
                        <div class="pigeon-description">
                            <h3><i class="fas fa-info-circle"></i> Sobre este pombo</h3>
                            <p><?php echo nl2br(htmlspecialchars($pigeonData['description'])); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="owner-info">
                        <h3><i class="fas fa-user"></i> Informações do Vendedor</h3>
                        <p><strong>Vendedor:</strong> <?php echo htmlspecialchars($pigeonData['username'] ?? 'Anônimo'); ?></p>
                        <p><strong>Pombos cadastrados:</strong> <?php echo count($pigeon->getAllByUser($pigeonData['user_id'])); ?></p>
                        <p><strong>Membro desde:</strong> <?php echo date('m/Y', strtotime($pigeonData['created_at'])); ?></p>
                    </div>

                    <div class="quantity-selector">
                        <label for="quantity"><strong>Quantidade:</strong></label>
                        <input type="number" id="quantity" class="quantity-input" value="1" min="1" max="10">
                    </div>

                    <div class="pigeon-actions">
                        <?php if ($cart->hasItem($pigeonData['id'])): ?>
                            <button class="btn-large btn-secondary" onclick="removeFromCart(<?php echo $pigeonData['id']; ?>)">
                                <i class="fas fa-check"></i> No Carrinho - Remover
                            </button>
                        <?php else: ?>
                            <button class="btn-large btn-success" onclick="addToCartWithQuantity(<?php echo $pigeonData['id']; ?>)">
                                <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                            </button>
                        <?php endif; ?>
                        
                        <button class="btn-large btn-primary" onclick="buyNow(<?php echo $pigeonData['id']; ?>)">
                            <i class="fas fa-credit-card"></i> Comprar Agora
                        </button>
                    </div>
                </div>
            </div>

            <?php if (!empty($otherPigeons)): ?>
                <div class="other-pigeons">
                    <h2>Outros pombos do mesmo vendedor</h2>
                    <div class="other-pigeons-grid">
                        <?php foreach ($otherPigeons as $other): ?>
                            <div class="mini-pigeon-card" onclick="window.location.href='pigeon-detail.php?id=<?php echo $other['id']; ?>'">
                                <?php if ($other['image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($other['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($other['name']); ?>" 
                                         class="mini-pigeon-image"
                                         onerror="this.src='./img/pru.jpg'">
                                <?php else: ?>
                                    <div class="mini-pigeon-image" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-dove" style="color: #6c757d;"></i>
                                    </div>
                                <?php endif; ?>
                                <h4><?php echo htmlspecialchars($other['name']); ?></h4>
                                <p><?php echo htmlspecialchars($other['color'] ?: 'Sem cor'); ?></p>
                                <?php 
                                srand($other['id']); 
                                $otherPrice = rand(15, 150); 
                                ?>
                                <strong>R$ <?php echo number_format($otherPrice, 2, ',', '.'); ?></strong>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => document.body.removeChild(toast), 300);
            }, 3000);
        }
        
        // Update cart count in navigation
        function updateCartCount(count) {
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
                cartCountElement.style.transform = 'scale(1.5)';
                setTimeout(() => {
                    cartCountElement.style.transform = 'scale(1)';
                }, 200);
            }
        }
        
        // Add to cart with quantity
        function addToCartWithQuantity(pigeonId) {
            const quantity = document.getElementById('quantity').value;
            const button = document.querySelector('.btn-success');
            
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adicionando...';
            }
            
            fetch('cart-actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=add&pigeon_id=${pigeonId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount(data.cart_count);
                    showToast(`${quantity} pombo(s) adicionado(s) ao carrinho!`, 'success');
                    
                    // Update button
                    if (button) {
                        button.className = 'btn-large btn-secondary';
                        button.innerHTML = '<i class="fas fa-check"></i> No Carrinho - Remover';
                        button.onclick = () => removeFromCart(pigeonId);
                    }
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro ao adicionar ao carrinho', 'error');
            })
            .finally(() => {
                if (button) {
                    button.disabled = false;
                }
            });
        }
        
        // Remove from cart
        function removeFromCart(pigeonId) {
            const button = document.querySelector('.btn-secondary');
            
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Removendo...';
            }
            
            fetch('cart-actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=remove&pigeon_id=${pigeonId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount(data.cart_count);
                    showToast('Pombo removido do carrinho!', 'success');
                    
                    // Update button
                    if (button) {
                        button.className = 'btn-large btn-success';
                        button.innerHTML = '<i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho';
                        button.onclick = () => addToCartWithQuantity(pigeonId);
                    }
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro ao remover do carrinho', 'error');
            })
            .finally(() => {
                if (button) {
                    button.disabled = false;
                }
            });
        }
        
        // Buy now function
        function buyNow(pigeonId) {
            const quantity = document.getElementById('quantity').value;
            
            // First add to cart, then redirect to cart
            fetch('cart-actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=add&pigeon_id=${pigeonId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to cart
                    window.location.href = 'cart.php';
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro ao processar compra', 'error');
            });
        }
        
        // Load cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            fetch('cart-actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_count'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount(data.cart_count);
                }
            })
            .catch(error => console.error('Erro ao carregar contador:', error));
        });
    </script>
</body>
</html>
