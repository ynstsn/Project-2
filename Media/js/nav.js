// Script pour généré le hamburger et de garder le responsif (gestion de la modification de la taille de fenêtre) 

const body = document.querySelector("body");
const hamburger = document.querySelector(".hamburger");
const navLinks = document.querySelector(".nav_header");

hamburger.addEventListener('click', ()=>{
    navLinks.classList.toggle("open");
    body.classList.toggle("open");
    hamburger.classList.toggle("open");

});

// Permet l'actualisation et la verification si le hamburger doit apparaitre ou non selon la taille de la fenêtre
function updateScreenSize() {
    var largeurEcran = window.innerWidth;
    if(largeurEcran >= 1280){
        navLinks.classList.remove("open");
        body.classList.remove("open");
        hamburger.classList.remove("open");
    } 
}
  
window.addEventListener('resize', updateScreenSize);


  