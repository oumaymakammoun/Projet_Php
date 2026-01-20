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
let highScore = localStorage.removeItem('ascensionHighScore') || 0; 
let currentConfig = {};  
let totalInstruments = 0;


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

function finJeu() {
    jeuActif = false;
    if(timerCreation) clearTimeout(timerCreation); 
    
    zoneJeu.innerHTML = '';

    let msg = `Score final : ${score}`;
    if (score > highScore) {
        highScore = score;
        localStorage.setItem('ascensionHighScore', highScore);
        msg += `<br><span style="color:gold; font-size:1.5rem">NOUVEAU RECORD ! 🏆</span>`;
        afficheHighScore.innerText = highScore;
    } else {
        msg += `<br>Record à battre : ${highScore}`;
    }

    ecranFin.style.display = 'flex';
    titreModal.innerText = "Game Over";
    messageModal.innerHTML = msg;
    
    btnPrePlay.innerText = "Rejouer";
}
