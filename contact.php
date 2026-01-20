<?php
session_start();

$message_success = '';
$message_error = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom']) && isset($_POST['message'])) {
    $nom = trim($_POST['nom']);
    $message = trim($_POST['message']);
    
    if (!empty($nom) && !empty($message)) {
        // Sécuriser les données
        $nom = htmlspecialchars($nom, ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        $date = date('Y-m-d H:i:s');
        
        // Sauvegarder dans un fichier (ou envoyer par email, ou base de données)
        $fichierFeedback = 'feedback.txt';
        $ligne = $date . ' | ' . $nom . ' | ' . $message . "\n";
        file_put_contents($fichierFeedback, $ligne, FILE_APPEND);
        
        $message_success = 'Merci pour votre message ! Nous avons bien reçu votre feedback.';
        
        // Vider les champs du formulaire
        $nom = '';
        $message = '';
    } else {
        $message_error = 'Veuillez remplir tous les champs.';
    }
}

$pseudo = isset($_SESSION['pseudo']) ? $_SESSION['pseudo'] : 'Invité';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Ascension Musicale</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('https://images.unsplash.com/photo-1511379938547-c1f69419868d?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            padding: 20px;
        }
        .contact-container {
            max-width: 600px;
            margin: 50px auto;
            background: rgba(0, 0, 0, 0.9);
            padding: 40px;
            border-radius: 20px;
            border: 2px solid #a64aff;
            box-shadow: 0 0 30px #a64aff;
        }
        .contact-container h1 {
            color: #a64aff;
            text-shadow: 0 0 10px #a64aff;
            text-align: center;
            margin-bottom: 30px;
        }
        .contact-form label {
            display: block;
            color: #ccc;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 2px solid #666;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 1rem;
            font-family: 'Nunito', sans-serif;
            box-sizing: border-box;
        }
        .contact-form input:focus,
        .contact-form textarea:focus {
            outline: none;
            border-color: #a64aff;
            box-shadow: 0 0 10px #a64aff;
        }
        .contact-form textarea {
            min-height: 150px;
            resize: vertical;
        }
        .contact-form button {
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
        .contact-form button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px #a64aff;
        }
        .message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .message.success {
            background: rgba(46, 204, 113, 0.2);
            border: 2px solid #2ecc71;
            color: #2ecc71;
        }
        .message.error {
            background: rgba(231, 76, 60, 0.2);
            border: 2px solid #e74c3c;
            color: #e74c3c;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #a64aff;
            text-decoration: none;
            padding: 10px 20px;
            border: 2px solid #a64aff;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .back-link:hover {
            background: #a64aff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <h1>📝 Donner mon avis</h1>
        <p style="color: #ccc; text-align: center; margin-bottom: 30px;">
            Vous avez trouvé un bug ? Une idée d'amélioration ? N'hésitez pas à nous le faire savoir !
        </p>
        
        <?php if ($message_success): ?>
            <div class="message success"><?php echo htmlspecialchars($message_success); ?></div>
        <?php endif; ?>
        
        <?php if ($message_error): ?>
            <div class="message error"><?php echo htmlspecialchars($message_error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" class="contact-form">
            <label for="nom">Nom / Pseudo :</label>
            <input type="text" id="nom" name="nom" placeholder="Votre nom ou pseudo" required value="<?php echo isset($nom) ? htmlspecialchars($nom) : htmlspecialchars($pseudo); ?>">
            
            <label for="message">Message :</label>
            <textarea id="message" name="message" placeholder="Décrivez votre feedback, bug trouvé ou suggestion d'amélioration..." required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
            
            <button type="submit">Envoyer</button>
        </form>
        
        <div style="text-align: center;">
            <a href="index.php" class="back-link">← Retour au jeu</a>
        </div>
    </div>
</body>
</html>
