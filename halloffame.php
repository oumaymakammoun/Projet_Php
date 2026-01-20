<?php
session_start();

// Lire les scores depuis le fichier
$fichierScores = 'scores.txt';
$scores = [];

if (file_exists($fichierScores)) {
    $contenu = file_get_contents($fichierScores);
    if (!empty($contenu)) {
        $lignes = explode("\n", trim($contenu));
        foreach ($lignes as $ligne) {
            if (!empty($ligne)) {
                $parts = explode('|', $ligne);
                if (count($parts) === 3) {
                    $scores[] = [
                        'score' => intval($parts[0]),
                        'pseudo' => $parts[1],
                        'date' => $parts[2]
                    ];
                }
            }
        }
    }
}

// Trier par score décroissant
usort($scores, function($a, $b) {
    return $b['score'] - $a['score'];
});

// Garder les 10 meilleurs
$top10 = array_slice($scores, 0, 10);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall of Fame - Ascension Musicale</title>
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
        .hall-container {
            max-width: 800px;
            margin: 50px auto;
            background: rgba(0, 0, 0, 0.9);
            padding: 40px;
            border-radius: 20px;
            border: 2px solid #a64aff;
            box-shadow: 0 0 30px #a64aff;
        }
        .hall-container h1 {
            color: #a64aff;
            text-shadow: 0 0 10px #a64aff;
            text-align: center;
            margin-bottom: 30px;
        }
        .scores-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .scores-table th {
            background: rgba(166, 74, 255, 0.3);
            color: #a64aff;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #a64aff;
        }
        .scores-table td {
            padding: 15px;
            border-bottom: 1px solid #333;
            color: #ccc;
        }
        .scores-table tr:hover {
            background: rgba(166, 74, 255, 0.1);
        }
        .rank {
            font-weight: bold;
            font-size: 1.2rem;
            color: #ffcc00;
        }
        .rank-1 { color: #ffd700; font-size: 1.5rem; }
        .rank-2 { color: #c0c0c0; font-size: 1.4rem; }
        .rank-3 { color: #cd7f32; font-size: 1.3rem; }
        .score-value {
            color: #00d2ff;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .no-scores {
            text-align: center;
            color: #ccc;
            padding: 40px;
            font-size: 1.2rem;
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
    <div class="hall-container">
        <h1>🏆 Hall of Fame 🏆</h1>
        <p style="color: #ccc; text-align: center; margin-bottom: 30px;">Les meilleurs scores de tous les temps</p>
        
        <?php if (empty($top10)): ?>
            <div class="no-scores">
                Aucun score enregistré pour le moment. Soyez le premier à établir un record !
            </div>
        <?php else: ?>
            <table class="scores-table">
                <thead>
                    <tr>
                        <th>Rang</th>
                        <th>Pseudo</th>
                        <th>Score</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top10 as $index => $score): ?>
                        <tr>
                            <td>
                                <span class="rank rank-<?php echo $index + 1; ?>">
                                    <?php 
                                    $rank = $index + 1;
                                    if ($rank == 1) echo '🥇';
                                    elseif ($rank == 2) echo '🥈';
                                    elseif ($rank == 3) echo '🥉';
                                    else echo '#' . $rank;
                                    ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($score['pseudo']); ?></td>
                            <td><span class="score-value"><?php echo number_format($score['score']); ?></span></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($score['date'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <div style="text-align: center;">
            <a href="index.php" class="back-link">← Retour au jeu</a>
        </div>
    </div>
</body>
</html>
