<?php
// Fichier pour sauvegarder les scores
header('Content-Type: application/json');

session_start();

// Vérifier que c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

// Récupérer les données JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['score']) || !isset($_SESSION['pseudo'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Données manquantes']);
    exit;
}

$score = intval($data['score']);
$pseudo = $_SESSION['pseudo'];
$date = date('Y-m-d H:i:s');

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

// Ajouter le nouveau score
$scores[] = [
    'score' => $score,
    'pseudo' => $pseudo,
    'date' => $date
];

// Trier par score décroissant
usort($scores, function($a, $b) {
    return $b['score'] - $a['score'];
});

// Garder seulement les 100 meilleurs scores
$scores = array_slice($scores, 0, 100);

// Réécrire le fichier
$contenuNouveau = '';
foreach ($scores as $s) {
    $contenuNouveau .= $s['score'] . '|' . $s['pseudo'] . '|' . $s['date'] . "\n";
}
file_put_contents($fichierScores, $contenuNouveau);

// Retourner les 5 meilleurs scores
$top5 = array_slice($scores, 0, 5);

// Retourner une réponse JSON
echo json_encode([
    'success' => true,
    'top5' => $top5,
    'votreScore' => $score
]);
