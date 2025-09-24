<?php
session_start();
require_once __DIR__ . '/classes/Cart.php';
require_once __DIR__ . '/classes/Pigeon.php';

$cart = new Cart();
$cartItems = $cart->getItems();
$pigeon = new Pigeon();

// Buscar detalhes dos pombos no carrinho
$cartPigeons = [];
$total = 0;
foreach ($cartItems as $pigeonId => $quantity) {
    $pigeonData = $pigeon->getById($pigeonId);
    if ($pigeonData) {
        $price = rand(15, 150); // Preço simulado
        $cartPigeons[] = [
            'pigeon' => $pigeonData,
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $price * $quantity
        ];
        $total += $price * $quantity;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Pombo Retailing United</title>
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .cart-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .cart-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .cart-item {
            display: flex;
            align-items: center;
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .cart-item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }
        .cart-item-info {
            flex: 1;
        }
        .cart-item-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .quantity-btn {
            background: #007bff;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quantity-btn:hover {
            background: #0056b3;
        }
        .cart-summary {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-top: 30px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 1.2em;
            font-weight: bold;
            color: #007bff;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #eee;
        }
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-cart i {
            font-size: 4em;
            color: #ddd;
            margin-bottom: 20px;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
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
                <div class="nav-link cart-btn">
                    <a href="./cart.php" class="btn">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count"><?php echo $cart->getItemCount(); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="page">
        <div class="cart-container">
            <div class="cart-header">
                <h1><i class="fas fa-shopping-cart"></i> Meu Carrinho</h1>
                <p><?php echo count($cartPigeons); ?> pombos selecionados</p>
            </div>

            <?php if (empty($cartPigeons)): ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Seu carrinho está vazio</h3>
                    <p>Explore nossos pombos e adicione alguns ao carrinho!</p>
                    <a href="index.php" class="btn btn-primary">Ver Pombos</a>
                </div>
            <?php else: ?>
                <div class="cart-items">
                    <?php foreach ($cartPigeons as $item): ?>
                        <div class="cart-item" data-pigeon-id="<?php echo $item['pigeon']['id']; ?>">
                            <?php if ($item['pigeon']['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($item['pigeon']['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['pigeon']['name']); ?>" 
                                     class="cart-item-image"
                                     onerror="this.src='./img/pru.jpg'">
                            <?php else: ?>
                                <img src="./img/pru.jpg" alt="pigeon" class="cart-item-image">
                            <?php endif; ?>
                            
                            <div class="cart-item-info">
                                <h4><?php echo htmlspecialchars($item['pigeon']['name']); ?></h4>
                                <p><strong>Raça:</strong> <?php echo htmlspecialchars($item['pigeon']['breed'] ?: 'Não informada'); ?></p>
                                <p><strong>Cor:</strong> <?php echo htmlspecialchars($item['pigeon']['color'] ?: 'Não informada'); ?></p>
                                <p><strong>Preço unitário:</strong> R$ <?php echo number_format($item['price'], 2, ',', '.'); ?></p>
                            </div>
                            
                            <div class="cart-item-actions">
                                <div class="quantity-controls">
                                    <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['pigeon']['id']; ?>, <?php echo $item['quantity'] - 1; ?>)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span class="quantity"><?php echo $item['quantity']; ?></span>
                                    <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['pigeon']['id']; ?>, <?php echo $item['quantity'] + 1; ?>)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <div class="subtotal">
                                    <strong>R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></strong>
                                </div>
                                <button class="btn btn-danger" onclick="removeFromCart(<?php echo $item['pigeon']['id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <div class="total-row">
                        <span>Total: </span>
                        <span>R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
                    </div>
                    <div style="text-align: center; margin-top: 30px;">
                        <button class="btn btn-success" onclick="alert('Funcionalidade de checkout em desenvolvimento!')">
                            <i class="fas fa-credit-card"></i> Finalizar Compra
                        </button>
                        <a href="index.php" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Continuar Comprando
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // Funções do carrinho (reutilizadas)
        function showToast(message, type = 'success') {
            alert(message); // Simplified for cart page
        }
        
        function updateQuantity(pigeonId, newQuantity) {
            if (newQuantity < 1) {
                if (confirm('Deseja remover este pombo do carrinho?')) {
                    removeFromCart(pigeonId);
                }
                return;
            }
            
            fetch('cart-actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=update&pigeon_id=${pigeonId}&quantity=${newQuantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Reload to update prices
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro ao atualizar quantidade', 'error');
            });
        }
        
        function removeFromCart(pigeonId) {
            if (!confirm('Tem certeza que deseja remover este pombo do carrinho?')) {
                return;
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
                    location.reload(); // Reload page
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro ao remover do carrinho', 'error');
            });
        }
    </script>
</body>
</html>
