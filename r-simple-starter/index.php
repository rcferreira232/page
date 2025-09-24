<?php
// Simple PHP site converted from HTML
// Page: Home/Index
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
</head>
<body>
    <!-- nav -->
    <nav class="navbar">
        <div class="nav-center">
            <div class="nav-header">
                <a href="./index.php" class="nav-logo">
                    <img src="./img/pombo_logo.svg" alt="pombo retailing united">
                </a>
                <button type="button" class="btn nav-btn">
                    <i class="fas fa-align-justify"></i>
                </button>
            </div>
            <div class="nav-links">
                <a href="./index.php" class="nav-link">home</a>
                <a href="./about.php" class="nav-link">about</a>
                <a href="./tags.php" class="nav-link">tags</a>
                <a href="./base.php" class="nav-link">base</a>
                <div class="nav-link contact-btn">
                    <a href="./contact.php" class="btn">contact</a>
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
                <h4>pigeon</h4>
                <div class="tags-list">
                    <a href="./tag-template.php">black</a>
                    <a href="./tag-template.php">grey</a>
                    <a href="./tag-template.php">brown</a>
                    <a href="./tag-template.php">white</a>
                </div>
            </div>
            <div class="pigeons-list">
                <!-- #1 -->
                <a href="./single-pigeon.php" class="pigeon">
                    <img src="./img/pru.jpg" alt="pigeon" class="img pigeon-img">
                    <h5>black</h5>
                    <p>price : R$ 15,00  | name : rudolf</p>
                </a>
                <!-- #2 -->
                <a href="./single-pigeon.php" class="pigeon">
                    <img src="./img/pru.jpg" alt="pigeon" class="img pigeon-img">
                    <h5>grey</h5>
                    <p>price : R$ 15,00  | name : rudolf</p>
                </a>
                <!-- #3 -->
                <a href="./single-pigeon.php" class="pigeon">
                    <img src="./img/pru.jpg" alt="pigeon" class="img pigeon-img">
                    <h5>brown</h5>
                    <p>price : R$ 15,00  | name : rudolf</p>
                </a>
                <!-- #4 -->
                <a href="./single-pigeon.php" class="pigeon">
                    <img src="./img/pru.jpg" alt="pigeon" class="img pigeon-img">
                    <h5>white</h5>
                    <p>price : R$ 15,00  | name : rudolf</p>
                </a>
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
</body>
</html>