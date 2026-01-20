<?php
// Démarrage de la session pour stocker les données utilisateur
session_start();

// Vérifier si le pseudo existe en session, sinon rediriger vers login.php
if (!isset($_SESSION['pseudo'])) {
    header('Location: login.php');
    exit;
}

// Configuration PHP
error_reporting(E_ALL);
ini_set('display_errors', 0); // Changez à 1 pour le développement

// Variables PHP utiles
$page_title = "Ascension Musicale";
$page_description = "Jeu musical interactif - Attrapez les instruments pour créer l'harmonie !";
$pseudo = $_SESSION['pseudo'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header">
            <h1 class="title">🎵 Ascension Musicale 🎵</h1>
            <div class="element1">
                <div class="stat-box">
                    <span class="label">👤 Joueur</span>
                    <span id="pseudo-display" class="value"><?php echo htmlspecialchars($pseudo); ?></span>
                </div>
                <div class="element2">
                    <span class="etoile">⭐</span>
                    <span id="score">0</span>
                    <p class="label">Points</p>
                </div>
                <div class="coeur" id="coeur"></div>
                <div class="stat-box">
                    <span class="label">🏆 Record</span>
                    <span id="high-score" class="value">0</span>
                </div>
                <div class="vitesse" id="vitesse">
                    ⚡ Vitesse: <span id="vvv">1x</span>
                </div>
            </div>
        </div>
    </header>
    <main id="game-area"></main>
    <div class="instruments">
        <h3>Instruments attrapés</h3>
        <div class="instrument-counter">
            <span class="icon">🎹</span> <span class="count" id="count-piano">0</span>
        </div>
        <div class="instrument-counter">
            <span class="icon">🎷</span> <span class="count" id="count-saxo">0</span>
        </div>
        <div class="instrument-counter">
            <span class="icon">🎸</span> <span class="count" id="count-guitare">0</span>
        </div>
        <div class="instrument-counter">
            <span class="icon">🥁</span> <span class="count" id="count-tambour">0</span>
        </div>
        <div class="instrument-counter">
            <span class="icon">🎺</span> <span class="count" id="count-trompette">0</span>
        </div>
        <p>Total: <span id="total">0</span></p>
        <hr style="margin: 20px 0; border-color: #333;">
        <div style="text-align: center; margin-top: 20px;">
            <a href="halloffame.php" style="color: #a64aff; text-decoration: none; padding: 10px 15px; border: 2px solid #a64aff; border-radius: 10px; display: inline-block; margin: 5px; transition: all 0.3s;" onmouseover="this.style.background='#a64aff'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#a64aff';">
                🏆 Hall of Fame
            </a>
            <a href="contact.php" style="color: #00d2ff; text-decoration: none; padding: 10px 15px; border: 2px solid #00d2ff; border-radius: 10px; display: inline-block; margin: 5px; transition: all 0.3s;" onmouseover="this.style.background='#00d2ff'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#00d2ff';">
                💬 Donner mon avis
            </a>
        </div>
    </div>
    <hr>
    <div id="game" class="game">
        <div class="modal">
            <div class="music-note-anim">♪</div>
            <h2 id="modal-title">Ascension Musicale</h2>
            <p id="message">Attrapez les instruments pour créer l'harmonie !<br>Ne les laissez pas s'échapper.</p>
            <div id="hall-of-fame-display" style="margin: 20px 0; color: #ccc; font-size: 0.9rem; max-height: 200px; overflow-y: auto;"></div>
            <div id="menu-principal">
                <button id="btn-pre-play" class="jouer">▶ Jouer</button>
            </div>
            <div id="menu-difficulte" style="display: none;">
                <div class="diff-buttons">
                    <button class="btn-diff easy" onclick="choisirDifficulte('easy')">😎 Facile</button>
                    <button class="btn-diff normal" onclick="choisirDifficulte('normal')">😐 Normal</button>
                    <button class="btn-diff hard" onclick="choisirDifficulte('hard')">👿 Impossible</button>
                </div>
                <button id="btn-retour" class="btn-small">Retour</button>
            </div>
        </div>
    </div>
    <script src="indexJS.js"></script>
</body>
</html>
