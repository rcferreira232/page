<?php
session_start();
require_once __DIR__ . '/classes/Pigeon.php';
require_once __DIR__ . '/classes/Cart.php';

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

// Inicializar carrinho
$cart = new Cart();
$cartCount = $cart->getItemCount();
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
        
        /* Cart Styles */
        .cart-count {
            background: #dc3545;
            color: white;
            border-radius: 50%;
            font-size: 0.7em;
            position: absolute;
            top: -10px;
            right: -10px;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            line-height: 1;
            padding: 0;
        }
        
        .cart-btn {
            position: relative;
            overflow: visible !important;
        }
        
        .cart-btn .btn {
            overflow: visible !important;
            position: relative;
        }
        
        .nav-links {
            overflow: visible !important;
        }
        
        .nav-center {
            overflow: visible !important;
        }
        
        .navbar {
            overflow: visible !important;
        }
        
        .pigeon-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        
        .btn-cart {
            padding: 8px 15px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9em;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-add-cart {
            background: #28a745;
            color: white;
        }
        
        .btn-add-cart:hover {
            background: #218838;
            transform: scale(1.05);
        }
        
        .btn-in-cart {
            background: #6c757d;
            color: white;
        }
        
        .btn-in-cart:hover {
            background: #dc3545;
        }
        
        .pigeon-price {
            font-weight: bold;
            color: #007bff;
            font-size: 1.1em;
        }
        
        .pigeon-card-home {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .pigeon-card-home {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }
        
        .pigeon-clickable {
            flex: 1;
            transition: all 0.3s;
        }
        
        .pigeon-clickable:hover {
            transform: translateY(-2px);
        }
        
        .pigeon-actions {
            margin-top: auto;
            padding-top: 10px;
        }
        
        /* Loading states */
        .btn-cart:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        /* Toast notification */
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
                <div class="nav-link contact-btn cart-btn">
                    <a href="./cart.php" class="btn" id="cart-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="cart-count" class="cart-count"><?php echo $cartCount; ?></span>
                    </a>
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
                        <div class="pigeon pigeon-card-home" data-pigeon-id="<?php echo $p['id']; ?>">
                            <div class="pigeon-clickable" onclick="window.location.href='pigeon-detail.php?id=<?php echo $p['id']; ?>'" style="cursor: pointer;">
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
                            
                            <div class="pigeon-actions">
                                <?php if ($cart->hasItem($p['id'])): ?>
                                    <button class="btn-cart btn-in-cart" onclick="event.stopPropagation(); removeFromCart(<?php echo $p['id']; ?>)">
                                        <i class="fas fa-check"></i> No Carrinho
                                    </button>
                                <?php else: ?>
                                    <button class="btn-cart btn-add-cart" onclick="event.stopPropagation(); addToCart(<?php echo $p['id']; ?>)">
                                        <i class="fas fa-shopping-cart"></i> Adicionar
                                    </button>
                                <?php endif; ?>
                                <?php 
                                srand($p['id']); 
                                $cardPrice = rand(15, 150); 
                                ?>
                                <span class="pigeon-price">R$ <?php echo number_format($cardPrice, 2, ',', '.'); ?></span>
                            </div>
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
    
    <!-- Cart JavaScript -->
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
                
                // Add animation
                cartCountElement.style.transform = 'scale(1.5)';
                setTimeout(() => {
                    cartCountElement.style.transform = 'scale(1)';
                }, 200);
            }
        }
        
        // Add to cart function
        function addToCart(pigeonId) {
            const button = document.querySelector(`[data-pigeon-id="${pigeonId}"] .btn-add-cart`);
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adicionando...';
            }
            
            fetch('cart-actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=add&pigeon_id=${pigeonId}&quantity=1`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount(data.cart_count);
                    showToast(data.message, 'success');
                    
                    // Update button state
                    if (button) {
                        button.className = 'btn-cart btn-in-cart';
                        button.innerHTML = '<i class="fas fa-check"></i> No Carrinho';
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
        
        // Remove from cart function
        function removeFromCart(pigeonId) {
            const button = document.querySelector(`[data-pigeon-id="${pigeonId}"] .btn-in-cart`);
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
                    showToast(data.message, 'success');
                    
                    // Update button state
                    if (button) {
                        button.className = 'btn-cart btn-add-cart';
                        button.innerHTML = '<i class="fas fa-shopping-cart"></i> Adicionar';
                        button.onclick = () => addToCart(pigeonId);
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