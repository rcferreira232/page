<?php
session_start();
require_once __DIR__ . '/classes/User.php';

$error = '';
$success = '';

if ($_POST) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $action = $_POST['action'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Por favor, preencha todos os campos.';
    } else {
        $user = new User();
        
        if ($action === 'register') {
            if (strlen($password) < 4) {
                $error = 'A senha deve ter pelo menos 4 caracteres.';
            } else {
                if ($user->register($username, $password)) {
                    $success = 'Usuário cadastrado com sucesso! Faça login.';
                } else {
                    $error = 'Erro ao cadastrar usuário. Nome já pode estar em uso.';
                }
            }
        } elseif ($action === 'login') {
            $userData = $user->login($username, $password);
            if ($userData) {
                $_SESSION['user_id'] = $userData['id'];
                $_SESSION['username'] = $userData['username'];
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Usuário ou senha incorretos.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pombo Retailing United</title>
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .auth-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
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
        .form-group input {
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-group input:focus {
            outline: none;
            border-color: #007bff;
        }
        .auth-btn {
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .auth-btn:hover {
            background: #0056b3;
        }
        .auth-btn.secondary {
            background: #6c757d;
        }
        .auth-btn.secondary:hover {
            background: #545b62;
        }
        .error {
            color: #dc3545;
            text-align: center;
            margin-bottom: 15px;
        }
        .success {
            color: #28a745;
            text-align: center;
            margin-bottom: 15px;
        }
        .auth-toggle {
            text-align: center;
            margin-top: 20px;
        }
        .auth-toggle button {
            background: none;
            border: none;
            color: #007bff;
            text-decoration: underline;
            cursor: pointer;
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
                <a href="./about.php" class="nav-link">about</a>
            </div>
        </div>
    </nav>

    <main class="page">
        <div class="auth-container">
            <h2 id="form-title">Login</h2>
            
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <input type="hidden" name="action" id="action" value="login">
                
                <div class="form-group">
                    <label for="username">Nome de usuário:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="auth-btn" id="submit-btn">Entrar</button>
            </form>
            
            <div class="auth-toggle">
                <p>Não tem conta? <button type="button" onclick="toggleForm()" id="toggle-btn">Cadastre-se</button></p>
            </div>
        </div>
    </main>

    <script>
        function toggleForm() {
            const title = document.getElementById('form-title');
            const action = document.getElementById('action');
            const submitBtn = document.getElementById('submit-btn');
            const toggleBtn = document.getElementById('toggle-btn');
            const toggleText = toggleBtn.parentElement;
            
            if (action.value === 'login') {
                title.textContent = 'Cadastro';
                action.value = 'register';
                submitBtn.textContent = 'Cadastrar';
                toggleText.innerHTML = 'Já tem conta? <button type="button" onclick="toggleForm()" id="toggle-btn">Faça login</button>';
            } else {
                title.textContent = 'Login';
                action.value = 'login';
                submitBtn.textContent = 'Entrar';
                toggleText.innerHTML = 'Não tem conta? <button type="button" onclick="toggleForm()" id="toggle-btn">Cadastre-se</button>';
            }
        }
    </script>
</body>
</html>
