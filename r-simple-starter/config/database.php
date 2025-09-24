<?php
class Database {
    private $db;
    
    public function __construct() {
        try {
            // Caminho absoluto para o banco
            $dbPath = dirname(__DIR__) . '/database.db';
            $this->db = new PDO('sqlite:' . $dbPath);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    private function createTables() {
        // Tabela de usuários
        $userTable = "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        
        // Tabela de pombos
        $pigeonTable = "CREATE TABLE IF NOT EXISTS pigeons (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            breed TEXT,
            color TEXT,
            age INTEGER,
            description TEXT,
            image_url TEXT,
            user_id INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users (id)
        )";
        
        $this->db->exec($userTable);
        $this->db->exec($pigeonTable);
    }
    
    public function getConnection() {
        return $this->db;
    }
}
?>
