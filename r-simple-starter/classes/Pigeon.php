<?php
require_once __DIR__ . '/../config/database.php';

class Pigeon {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function create($name, $breed, $color, $age, $description, $user_id) {
        try {
            $stmt = $this->db->prepare("INSERT INTO pigeons (name, breed, color, age, description, user_id) VALUES (?, ?, ?, ?, ?, ?)");
            return $stmt->execute([$name, $breed, $color, $age, $description, $user_id]);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function getAllByUser($user_id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM pigeons WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function getAll() {
        try {
            $stmt = $this->db->prepare("
                SELECT p.*, u.username 
                FROM pigeons p 
                LEFT JOIN users u ON p.user_id = u.id 
                ORDER BY p.created_at DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function getById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM pigeons WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function update($id, $name, $breed, $color, $age, $description, $user_id) {
        try {
            $stmt = $this->db->prepare("UPDATE pigeons SET name = ?, breed = ?, color = ?, age = ?, description = ? WHERE id = ? AND user_id = ?");
            return $stmt->execute([$name, $breed, $color, $age, $description, $id, $user_id]);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function delete($id, $user_id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM pigeons WHERE id = ? AND user_id = ?");
            return $stmt->execute([$id, $user_id]);
        } catch(PDOException $e) {
            return false;
        }
    }
}
?>
