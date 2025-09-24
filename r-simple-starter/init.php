<?php
// Arquivo para inicializar o banco de dados
require_once __DIR__ . '/config/database.php';

try {
    $db = new Database();
    echo "✅ Banco de dados inicializado com sucesso!\n";
    echo "📁 Arquivo: database.db criado\n";
    echo "📋 Tabelas: users e pigeons criadas\n";
    echo "\n🚀 Sistema pronto para uso!\n";
    echo "🌐 Acesse: http://localhost:8001\n";
} catch(Exception $e) {
    echo "❌ Erro ao inicializar banco: " . $e->getMessage() . "\n";
}
?>
