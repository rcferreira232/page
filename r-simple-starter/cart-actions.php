<?php
session_start();
require_once __DIR__ . '/classes/Cart.php';
require_once __DIR__ . '/classes/Pigeon.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$action = $_POST['action'] ?? '';
$pigeonId = intval($_POST['pigeon_id'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 1);

$cart = new Cart();
$response = ['success' => false, 'message' => ''];

switch ($action) {
    case 'add':
        if ($pigeonId > 0) {
            // Verificar se o pombo existe
            $pigeon = new Pigeon();
            $pigeonData = $pigeon->getById($pigeonId);
            
            if ($pigeonData) {
                $cart->addItem($pigeonId, $quantity);
                $response = [
                    'success' => true,
                    'message' => 'Pombo adicionado ao carrinho!',
                    'cart_count' => $cart->getItemCount(),
                    'cart_items' => $cart->getTotalItems()
                ];
            } else {
                $response['message'] = 'Pombo não encontrado';
            }
        } else {
            $response['message'] = 'ID do pombo inválido';
        }
        break;
        
    case 'remove':
        if ($pigeonId > 0) {
            $cart->removeItem($pigeonId);
            $response = [
                'success' => true,
                'message' => 'Pombo removido do carrinho!',
                'cart_count' => $cart->getItemCount(),
                'cart_items' => $cart->getTotalItems()
            ];
        } else {
            $response['message'] = 'ID do pombo inválido';
        }
        break;
        
    case 'update':
        if ($pigeonId > 0) {
            $cart->updateQuantity($pigeonId, $quantity);
            $response = [
                'success' => true,
                'message' => 'Quantidade atualizada!',
                'cart_count' => $cart->getItemCount(),
                'cart_items' => $cart->getTotalItems()
            ];
        } else {
            $response['message'] = 'ID do pombo inválido';
        }
        break;
        
    case 'get_count':
        $response = [
            'success' => true,
            'cart_count' => $cart->getItemCount(),
            'cart_items' => $cart->getTotalItems()
        ];
        break;
        
    default:
        $response['message'] = 'Ação inválida';
        break;
}

echo json_encode($response);
?>
