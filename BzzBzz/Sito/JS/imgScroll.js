const immagini = [
    "../Img/Vespe/50.jpg",
    "../Img/Vespe/50special.jpg",
    "../Img/Vespe/pk125.jpg",
    "../Img/Vespe/px125.jpg"
];

let indice = 0;
let timer; // Variabile per memorizzare l'intervallo
const imgElement = document.getElementById("fading-image");

function cambiaImmagine() {
    imgElement.classList.add("fade-out");

    setTimeout(() => {
        indice = (indice + 1) % immagini.length;
        imgElement.src = immagini[indice];
        imgElement.classList.remove("fade-out");
    }, 1000); 
}

// Funzione per avviare lo slideshow
function avviaSlideshow() {
    timer = setInterval(cambiaImmagine, 2000);
}

// Funzione per fermare lo slideshow
function fermaSlideshow() {
    clearInterval(timer);
}

// --- Gestione Eventi Mouse ---

// Quando il mouse entra, ferma il timer
imgElement.addEventListener("mouseenter", fermaSlideshow);

// Quando il mouse esce, riavvia il timer
imgElement.addEventListener("mouseleave", avviaSlideshow);

// Avvio iniziale
avviaSlideshow();