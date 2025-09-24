<?php
class Cart {
    
    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }
    
    public function addItem($pigeonId, $quantity = 1) {
        if (isset($_SESSION['cart'][$pigeonId])) {
            $_SESSION['cart'][$pigeonId] += $quantity;
        } else {
            $_SESSION['cart'][$pigeonId] = $quantity;
        }
        return true;
    }
    
    public function removeItem($pigeonId) {
        if (isset($_SESSION['cart'][$pigeonId])) {
            unset($_SESSION['cart'][$pigeonId]);
            return true;
        }
        return false;
    }
    
    public function updateQuantity($pigeonId, $quantity) {
        if ($quantity <= 0) {
            return $this->removeItem($pigeonId);
        }
        $_SESSION['cart'][$pigeonId] = $quantity;
        return true;
    }
    
    public function getItems() {
        return $_SESSION['cart'] ?? [];
    }
    
    public function getItemCount() {
        return array_sum($_SESSION['cart'] ?? []);
    }
    
    public function getTotalItems() {
        return count($_SESSION['cart'] ?? []);
    }
    
    public function clear() {
        $_SESSION['cart'] = [];
        return true;
    }
    
    public function hasItem($pigeonId) {
        return isset($_SESSION['cart'][$pigeonId]);
    }
    
    public function getItemQuantity($pigeonId) {
        return $_SESSION['cart'][$pigeonId] ?? 0;
    }
}
?>
