// Cette fonction permet de généré une fenetre Pop up pour valider un traitement
function pop(titre, desc, fonction, id) { 
    var form = document.getElementById(id);
    var body = document.getElementsByTagName("body")[0];
    body.style.overflow = "hidden";
    document.documentElement.scrollTop = 0;

    if (form.checkValidity()) {
        let popContentSection = document.createElement("section");
        popContentSection.setAttribute("id", "pop_content");
        popContentSection.style.display = "flex";

        let popSection = document.createElement("section");
        popSection.setAttribute("id", "pop");

        let titleElement = document.createElement("h1");
        titleElement.setAttribute("id", "title");
        titleElement.textContent = titre;

        let descElement = document.createElement("p");
        descElement.setAttribute("id", "desc");
        descElement.textContent = desc;

        let divElement = document.createElement("div");

        let cancelButton = document.createElement("button");
        cancelButton.setAttribute("class", "sign2");
        cancelButton.textContent = "Annuler";

        cancelButton.addEventListener("click", function () {
            popContentSection.style.display = "none";
        });

        let validateButton = document.createElement("button");
        validateButton.setAttribute("class", "sign2");
        validateButton.setAttribute("id", "val");
        validateButton.textContent = "Valider";
        validateButton.onclick = function () {
            window[fonction](id);
        };

        divElement.appendChild(cancelButton);
        divElement.appendChild(validateButton);

        popSection.appendChild(titleElement);
        popSection.appendChild(descElement);
        popSection.appendChild(divElement);

        popContentSection.appendChild(popSection);

        let parentElement = document.querySelector("main");
        parentElement.appendChild(popContentSection);
    } else {
        erro_logs("error", "Veuillez remplir tous les champs obligatoires.");
    }

}

function erro_logs(type, message) { // Permet de généré une notifiaction pour afficher des messages 

    let divError = document.createElement("div");
    divError.className = "div_error";
    divError.style.display = "flex";
    let svgElement, pathElement;

    if (type == "error") {

        divError.style.backgroundColor = "var(--error)";
        svgElement = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svgElement.setAttribute("xmlns", "http://www.w3.org/2000/svg");
        svgElement.setAttribute("viewBox", "0 0 30 30");
        svgElement.setAttribute("width", "60px");
        svgElement.setAttribute("height", "60px");

        pathElement = document.createElementNS("http://www.w3.org/2000/svg", "path");
        pathElement.setAttribute("d", "M 15 3 C 11.783059 3 8.8641982 4.2807926 6.7070312 6.3496094 A 1.0001 1.0001 0 0 0 6.3476562 6.7070312 C 4.2793766 8.8641071 3 11.783531 3 15 C 3 21.615572 8.3844276 27 15 27 C 18.210007 27 21.123475 25.724995 23.279297 23.664062 A 1.0001 1.0001 0 0 0 23.662109 23.28125 C 25.724168 21.125235 27 18.210998 27 15 C 27 8.3844276 21.615572 3 15 3 z M 15 5 C 20.534692 5 25 9.4653079 25 15 C 25 17.40637 24.155173 19.609062 22.746094 21.332031 L 8.6679688 7.2539062 C 10.390938 5.8448274 12.59363 5 15 5 z M 7.2539062 8.6679688 L 21.332031 22.746094 C 19.609062 24.155173 17.40637 25 15 25 C 9.4653079 25 5 20.534692 5 15 C 5 12.59363 5.8448274 10.390938 7.2539062 8.6679688 z");

        svgElement.appendChild(pathElement);

    } else {

        divError.style.backgroundColor = "var(--success)";
        svgElement = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svgElement.setAttribute("xmlns", "http://www.w3.org/2000/svg");
        svgElement.setAttribute("viewBox", "0 0 50 50");
        svgElement.setAttribute("width", "50px");
        svgElement.setAttribute("height", "50px");

        pathElement = document.createElementNS("http://www.w3.org/2000/svg", "path");
        pathElement.setAttribute("d", "M 41.9375 8.625 C 41.273438 8.648438 40.664063 9 40.3125 9.5625 L 21.5 38.34375 L 9.3125 27.8125 C 8.789063 27.269531 8.003906 27.066406 7.28125 27.292969 C 6.5625 27.515625 6.027344 28.125 5.902344 28.867188 C 5.777344 29.613281 6.078125 30.363281 6.6875 30.8125 L 20.625 42.875 C 21.0625 43.246094 21.640625 43.410156 22.207031 43.328125 C 22.777344 43.242188 23.28125 42.917969 23.59375 42.4375 L 43.6875 11.75 C 44.117188 11.121094 44.152344 10.308594 43.78125 9.644531 C 43.410156 8.984375 42.695313 8.589844 41.9375 8.625 Z");

        svgElement.appendChild(pathElement);

    }

    divError.appendChild(svgElement);

    var messageError = document.createElement("p");
    messageError.id = "message_error";
    messageError.textContent = message;

    divError.appendChild(messageError);

    document.querySelector("main").appendChild(divError);

    setTimeout(() => {
        divError.classList.add("hidden");

        setTimeout(() => {
            divError.style.display = "none";
        }, 2000); 
    }, 5000);


}

function pop_traitement(id) { // Permet de traiter de valider le formulaire et donc envoye les informations par la methode POST
    document.getElementById(id).submit();
    var body = document.getElementsByTagName("body")[0];
    body.style.overflow = "flex";
}

function previewImages(preview, imageInput) { // Permet de visualiser les photos enregistrer avant de confirmer l'envoie
    var preview = document.getElementById(preview);
    var fileInput = document.getElementById(imageInput);

    var file = fileInput.files[0];
    var reader = new FileReader();

    reader.onload = function (e) {
        preview.src = e.target.result;
    };

    reader.readAsDataURL(file);
}

function checkOptionSelected(id, value, type){ // Permet de préselectionner la donnée deja connu de la BDD dans une balise HTML Select que ce soit selon une value ou une nom

    let parent = document.getElementById(id).childNodes;

    parent.forEach(function(element) {
        if(type == 'value'){
            el = element.value
        } else {
            el = element.textContent
        }
        if (el == value) {
            element.selected = true
        }
    })
}

function ligneIsSelected(id){ // Permet de donner un style a la ligne selectionné du tableau
    let ligne = document.getElementById("ligne_"+id);
    ligne.classList.add("isSelect")
}

function createSvgElement(tag, className) {
    var svgElement = document.createElementNS('http://www.w3.org/2000/svg', tag);
    if (className) {
        svgElement.className = className;
    }
    return svgElement;
}