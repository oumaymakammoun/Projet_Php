<?php
// Fichier pour récupérer les scores sans les modifier
header('Content-Type: application/json');

session_start();

// Nom du fichier de scores
$fichierScores = 'scores.txt';

// Lire les scores existants
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

// Retourner les 5 meilleurs scores
$top5 = array_slice($scores, 0, 5);

echo json_encode([
    'success' => true,
    'top5' => $top5
]);
