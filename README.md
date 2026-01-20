# Projet_Php - Ascension Musicale

Jeu musical interactif développé en PHP, JavaScript et CSS.

## 🚀 Installation et Démarrage

### Prérequis
- PHP 7.4 ou supérieur installé sur votre système

### Démarrer le serveur

1. **Ouvrir un terminal/PowerShell** dans le dossier du projet :
   ```
   cd C:\Users\User\Desktop\Projet_web
   ```

2. **Démarrer le serveur PHP intégré** :
   ```bash
   php -S localhost:8000
   ```

3. **Ouvrir votre navigateur** et accéder à :
   ```
   http://localhost:8000/login.php
   ```

### ⚠️ IMPORTANT
Ne pas ouvrir les fichiers `.php` directement dans le navigateur (protocole `file:///`) car :
- PHP nécessite un serveur web pour fonctionner
- Les requêtes `fetch` sont bloquées par la sécurité du navigateur
- Les sessions PHP ne fonctionnent pas

## 📁 Structure du Projet

- `login.php` - Page de connexion (demande le pseudo)
- `index.php` - Page principale du jeu
- `contact.php` - Formulaire de feedback
- `halloffame.php` - Tableau des meilleurs scores
- `sauvegarder.php` - API pour sauvegarder les scores
- `get_scores.php` - API pour récupérer les scores
- `indexJS.js` - Logique JavaScript du jeu
- `style.css` - Styles CSS
- `scores.txt` - Fichier de sauvegarde des scores (créé automatiquement)
- `feedback.txt` - Fichier de sauvegarde des feedbacks (créé automatiquement)

## 🎮 Fonctionnalités

1. **Gestion de Session** : Connexion avec pseudo stocké en session PHP
2. **Hall of Fame** : Sauvegarde des scores dans un fichier texte
3. **Formulaire de Feedback** : Possibilité de signaler des bugs ou donner son avis
