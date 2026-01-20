<?php
session_start();

// Si le pseudo est déjà en session, rediriger vers index.php
if (isset($_SESSION['pseudo'])) {
    header('Location: index.php');
    exit;
}

// Traitement du formulaire
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pseudo'])) {
    $pseudo = trim($_POST['pseudo']);
    
    if (!empty($pseudo) && strlen($pseudo) <= 20) {
        // Sécuriser le pseudo
        $pseudo = htmlspecialchars($pseudo, ENT_QUOTES, 'UTF-8');
        $_SESSION['pseudo'] = $pseudo;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Veuillez entrer un pseudo valide (max 20 caractères)';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Ascension Musicale</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('https://images.unsplash.com/photo-1511379938547-c1f69419868d?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }
        .login-container {
            background: rgba(0, 0, 0, 0.9);
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            border: 2px solid #a64aff;
            box-shadow: 0 0 30px #a64aff;
            max-width: 400px;
            width: 90%;
        }
        .login-container h1 {
            color: #a64aff;
            text-shadow: 0 0 10px #a64aff;
            margin-bottom: 30px;
        }
        .login-form input {
            width: 100%;
            padding: 15px;
            margin-bottom: 15px;
            border: 2px solid #666;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 1.1rem;
            box-sizing: border-box;
        }
        .login-form input:focus {
            outline: none;
            border-color: #a64aff;
            box-shadow: 0 0 10px #a64aff;
        }
        .login-form button {
            width: 100%;
            padding: 15px;
            background: #a64aff;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .login-form button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px #a64aff;
        }
        .error {
            color: #ff4757;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>🎵 Ascension Musicale 🎵</h1>
        <p style="color: #ccc; margin-bottom: 30px;">Entrez votre pseudo pour commencer à jouer</p>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" class="login-form">
            <input type="text" name="pseudo" placeholder="Votre pseudo" required maxlength="20" autofocus>
            <button type="submit">▶ Commencer à jouer</button>
        </form>
    </div>
</body>
</html>
