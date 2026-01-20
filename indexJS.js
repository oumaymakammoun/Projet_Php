// --- CONFIGURATION ---
const instruments = [
    { type: 'inst-piano', icone: '🎹', idCompteur: 'count-piano', son: 'sounds/piano.mp3' },
    { type: 'inst-saxo', icone: '🎷', idCompteur: 'count-saxo', son: 'sounds/saxo.mp3' },
    { type: 'inst-guitare', icone: '🎸', idCompteur: 'count-guitare', son: 'sounds/guitare.mp3' },
    { type: 'inst-tambour', icone: '🥁', idCompteur: 'count-tambour', son: 'sounds/tambour.mp3' },
    { type: 'inst-trompette', icone: '🎺', idCompteur: 'count-trompette', son: 'sounds/trompette.mp3' }
];

const configNiveaux = {
    easy: {
        vitesseBase: 1.0,   
        intervalleBase: 700, 
        vies: 7,
        probPiege: 0.20,      
        
    },
    normal: {
        vitesseBase: 1.5,
        intervalleBase: 500,
        vies: 5,
        probPiege: 0.30,    
        
    },
    hard: {
        vitesseBase: 2.0,    
        intervalleBase: 100,  
        vies: 3,
        probPiege: 0.50,
       
    }
};


const zoneJeu = document.getElementById('game-area');
const afficheScore = document.getElementById('score');
const afficheTotal = document.getElementById('total');
const zoneCoeurs = document.getElementById('coeur');
const ecranFin = document.getElementById('game'); 
const afficheVitesse = document.getElementById('vvv');
const afficheHighScore = document.getElementById('high-score');

const menuPrincipal = document.getElementById('menu-principal');
const menuDifficulte = document.getElementById('menu-difficulte');
const btnPrePlay = document.getElementById('btn-pre-play');
const btnRetour = document.getElementById('btn-retour');
const messageModal = document.getElementById('message');
const titreModal = document.getElementById('modal-title');

let score = 0;
let vies = 0;
let jeuActif = false; 
let timerCreation;
let vitesseMultiplier = 1;
let highScore = parseInt(localStorage.getItem('ascensionHighScore')) || 0; 
let currentConfig = {};  
let totalInstruments = 0;
const hallOfFameDisplay = document.getElementById('hall-of-fame-display');

// Charger les top 5 scores au démarrage
chargerHallOfFame();

// Fonction pour charger le Hall of Fame
async function chargerHallOfFame() {
    try {
        // Lire les scores depuis le fichier via un endpoint PHP
        const response = await fetch('get_scores.php');
        if (response.ok) {
            const data = await response.json();
            if (data.success && data.top5 && data.top5.length > 0) {
                afficherTop5(data.top5);
                // Mettre à jour le high score global si nécessaire
                if (data.top5[0] && data.top5[0].score > highScore) {
                    highScore = data.top5[0].score;
                    afficheHighScore.innerText = highScore;
                    localStorage.setItem('ascensionHighScore', highScore);
                }
            }
        }
    } catch (error) {
        console.error('Erreur lors du chargement du Hall of Fame:', error);
    }
}

// Fonction pour afficher le top 5
function afficherTop5(top5) {
    if (!hallOfFameDisplay) return;
    
    let html = '<div style="background: rgba(166, 74, 255, 0.1); padding: 15px; border-radius: 10px; border: 1px solid #a64aff;">';
    html += '<h3 style="color: #a64aff; margin-bottom: 10px; font-size: 1rem;">🏆 Top 5 des meilleurs scores</h3>';
    html += '<ol style="text-align: left; color: #ccc; padding-left: 20px;">';
    
    top5.forEach((item, index) => {
        const rank = index + 1;
        const emoji = rank === 1 ? '🥇' : rank === 2 ? '🥈' : rank === 3 ? '🥉' : '•';
        html += `<li style="margin: 8px 0;">${emoji} <strong>${escapeHtml(item.pseudo)}</strong> - <span style="color: #00d2ff;">${item.score}</span></li>`;
    });
    
    html += '</ol></div>';
    hallOfFameDisplay.innerHTML = html;
}

// Fonction pour échapper le HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

afficheHighScore.innerText = highScore;


btnPrePlay.addEventListener('click', () => {
    menuPrincipal.style.display = 'none';
    menuDifficulte.style.display = 'block';
    messageModal.innerText = "Choisissez votre niveau :";
});

btnRetour.addEventListener('click', () => {
    menuDifficulte.style.display = 'none';
    menuPrincipal.style.display = 'block';
    messageModal.innerText = "Prêt à attraper le rythme ?";
});

function choisirDifficulte(niveau) {
    if(configNiveaux[niveau]) {
        currentConfig = configNiveaux[niveau];
        startGame();
    } 
}


function startGame(){
    score = 0;
     totalInstruments = 0;
    jeuActif = true; 

    vies = currentConfig.vies; 
    vitesseMultiplier = currentConfig.vitesseBase;

    updateUI();
    afficheCoueurs();
    
    instruments.forEach(instr => {
        const compteur = document.getElementById(instr.idCompteur);
        if(compteur) compteur.innerText = 0;
    });
    
    zoneJeu.innerHTML = '';
    ecranFin.style.display = 'none';
    
    menuPrincipal.style.display = 'block';
    menuDifficulte.style.display = 'none';
    
    creerElement(); 
}

function updateUI() {
    afficheScore.innerText = score;
    afficheTotal.innerText = totalInstruments; 
    afficheVitesse.innerText = vitesseMultiplier.toFixed(1) + 'x';
}

function afficheCoueurs(){
    zoneCoeurs.innerHTML = "";
    for(let i=0; i<vies; i++){
        zoneCoeurs.innerHTML += '❤️';
    }
}

function creerElement() {
    if (!jeuActif) return;

    const rand = Math.random();
    if (rand < currentConfig.probPiege) {
        creerNote('piege');
    } else if (rand > 0.96) {
        creerNote('or');
    } else {
        creerNote('normal');
    }
    let delaiNext = currentConfig.intervalleBase / (vitesseMultiplier * 0.8);// j ai fait pour les notes
    if (delaiNext < 400) delaiNext = 400;

    timerCreation = setTimeout(creerElement, delaiNext); 
}

function creerNote(mode) {
    const note = document.createElement('div');
    note.classList.add('instrument');
    

    let choix;
    
    if (mode === 'piege') {
        note.classList.add('inst-piege');
        note.innerHTML = '💀';
        note.addEventListener('click', (e) => {
            
            declencherPiege(note, e);
        });
    } else if (mode === 'or') {
        note.classList.add('inst-gold');
        note.innerHTML = '🌟';
        note.addEventListener('click', (e) => {
            
            attraperBonus(note, e);
        });
    } else {
       
        choix = instruments[Math.floor(Math.random() * instruments.length)];
        note.classList.add(choix.type);
        note.innerHTML = choix.icone;
        note.addEventListener('click', (e) => {
            
            attraperInstrument(note, choix, e);
        });
    }
    
    const positionX = Math.floor(Math.random() * 80) + 10;
    note.style.left = positionX + '%';
    note.style.left = '-80px'; 
    
    zoneJeu.appendChild(note);
    bougerNote(note, mode);
}

function bougerNote(note, mode) {
    let positionY = -80;
    let vitessePropre = (2 + Math.random() * 2) * vitesseMultiplier;
    
    if (mode === 'or') vitessePropre *= 1.5; 

    let timerNote = setInterval(() => {
        if (!jeuActif || !document.body.contains(note)) {
            clearInterval(timerNote);
            if(note.parentNode) note.remove();
            return;
        }

        positionY += vitessePropre;
        note.style.left  = positionY + 'px';
        
        if (positionY > zoneJeu.offsetHeight) {
            clearInterval(timerNote);
            note.remove(); 
            
            if (mode === 'normal') {
                perdreVie();
            }
        }
    }, 20);
}

function attraperInstrument(note, infoInstrument, e) {
    if(!jeuActif) return;

    const audio = new Audio(infoInstrument.son);
    audio.currentTime = 0;
    audio.play(); 

    score += 10;
    totalInstruments++; 
    const compteur = document.getElementById(infoInstrument.idCompteur);
    if(compteur) compteur.innerText = parseInt(compteur.innerText) + 1;

    vitesseMultiplier += 0.05; 

    if(e) {
        createParticles(e.clientX, e.clientY, getComputedStyle(note).borderColor);
        showFloatingText(e.clientX, e.clientY, "+10");
    }

    updateUI();
    note.remove();
}

function attraperBonus(note, e) {
    if(!jeuActif) return;
    score += 100;
    if(e) {
        createParticles(e.clientX, e.clientY, 'gold');
        showFloatingText(e.clientX, e.clientY, "+100");
    }
    updateUI();
    note.remove();
}

function declencherPiege(note, e) {
    if(!jeuActif) return;

    if(e) {
        createParticles(e.clientX, e.clientY, 'red');
        showFloatingText(e.clientX, e.clientY, `BOOM! 💀`);
    }
    
    perdreVie();
    note.remove();
}

function perdreVie() {
    vies -= 1;
    afficheCoueurs();
    
    zoneJeu.style.boxShadow = "inset 0 0 50px red";
    setTimeout(() => zoneJeu.style.boxShadow = "none", 200);

    if (vies <= 0) {
        finJeu();
    }
}
function createParticles(x, y, color) {
    for (let i = 0; i < 8; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        particle.style.backgroundColor = color || 'white';
        particle.style.left = x + 'px';
        particle.style.top = y + 'px';
        
        const tx = (Math.random() - 0.5) * 100;
        const ty = (Math.random() - 0.5) * 100;
        
        particle.animate([
            { transform: 'translate(0,0) scale(1)', opacity: 1 },
            { transform: `translate(${tx}px, ${ty}px) scale(0)`, opacity: 0 }
        ], { duration: 600, easing: 'ease-out' });

        document.body.appendChild(particle);
        setTimeout(() => particle.remove(), 1000);
    }
}

function showFloatingText(x, y, text) {
    const el = document.createElement('div');
    el.classList.add('floating-text');
    el.innerText = text;
    el.style.left = x + 'px';
    el.style.top = y + 'px';
    zoneJeu.appendChild(el);
    setTimeout(() => el.remove(), 1000);
}

async function finJeu() {
    jeuActif = false;
    if(timerCreation) clearTimeout(timerCreation); 
    
    zoneJeu.innerHTML = '';

    let msg = `Score final : ${score}`;
    let nouveauRecordLocal = false;
    
    // Vérifier le record local
    if (score > highScore) {
        highScore = score;
        localStorage.setItem('ascensionHighScore', highScore);
        nouveauRecordLocal = true;
        afficheHighScore.innerText = highScore;
    }
    
    // Envoyer le score au serveur
    try {
        const response = await fetch('sauvegarder.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ score: score })
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                // Afficher le top 5 mis à jour
                if (data.top5 && data.top5.length > 0) {
                    afficherTop5(data.top5);
                    
                    // Vérifier si c'est un nouveau record global
                    const recordGlobal = data.top5[0];
                    if (recordGlobal && recordGlobal.score === score) {
                        msg += `<br><span style="color:gold; font-size:1.5rem">NOUVEAU RECORD MONDIAL ! 🌍🏆</span>`;
                        if (recordGlobal.pseudo) {
                            msg += `<br><span style="color:#00d2ff;">Félicitations ${escapeHtml(recordGlobal.pseudo)} !</span>`;
                        }
                    } else if (nouveauRecordLocal) {
                        msg += `<br><span style="color:gold; font-size:1.5rem">NOUVEAU RECORD PERSONNEL ! 🏆</span>`;
                    }
                    
                    // Afficher le record global
                    if (recordGlobal && recordGlobal.score !== score) {
                        msg += `<br><span style="color:#ccc;">Record mondial à battre : ${recordGlobal.score} par ${escapeHtml(recordGlobal.pseudo)}</span>`;
                    }
                    
                    // Mettre à jour le high score global
                    if (recordGlobal && recordGlobal.score > highScore) {
                        highScore = recordGlobal.score;
                        afficheHighScore.innerText = highScore;
                        localStorage.setItem('ascensionHighScore', highScore);
                    }
                }
            }
        }
    } catch (error) {
        console.error('Erreur lors de la sauvegarde du score:', error);
        if (nouveauRecordLocal) {
            msg += `<br><span style="color:gold; font-size:1.5rem">NOUVEAU RECORD ! 🏆</span>`;
        }
    }
    
    if (!nouveauRecordLocal && !msg.includes('Record mondial')) {
        msg += `<br>Record à battre : ${highScore}`;
    }

    ecranFin.style.display = 'flex';
    titreModal.innerText = "Game Over";
    messageModal.innerHTML = msg;
    
    btnPrePlay.innerText = "Rejouer";
}
