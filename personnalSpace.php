<?php
    require_once __DIR__ . "/autoload.php"; 
    $userDAO = new UserDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les utilisateurs
    $typePersonnelDAO = new TypePersonnelDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les types de personnels
    session_start();

    if (!isset($_SESSION["user"])) { ##On vérifie  si l'utilisateur n'est pas connecté sinon on l'envoie sur la page de connexion
        header("Location: index.php");
        die();
    } 

    if(!empty($_POST['name']) && !empty($_POST['prenom']) && !empty($_POST['dob']) && !empty($_POST['adresse']) && !empty($_POST['cp']) && !empty($_POST['ville'])){ ## On verifie si tout les champs sont entrées
        $nouveau = new User( $_SESSION["user"]->Id_Personnel, $_POST['name'], $_POST['prenom'], $_SESSION["user"]->Sexe, $_POST['adresse'], $_POST['ville'], $_POST['dob'], $typePersonnelDAO->getOneByString($_SESSION["user"]->Id_Type_Personnel), $_POST['cp'], $userDAO->findPseudo($_POST['name'], $_POST['prenom']));
        $userDAO->save($nouveau); ## On mets a jour l'utilisateurs
        $_SESSION['log_err'] = array(
            'type' => "success",
            'message' => "Modification réalisée avec succès."
        );
        header('Location: deconnexion.php');
        die;
    }

    $header = createHeader($_SESSION["user"]->Id_Type_Personnel); ## On initialise le header pour l'utilisateur selon son grade
    $panel = createPanel($_SESSION["user"]->Id_Type_Personnel); ## On initialise le header pour l'utilisateur selon son grade

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Espace Personnel · Médicalife</title>
    <meta charset="UTF-8">
    <meta name="author" content="Marwanito">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="Media/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="Media/css/style.css">
    <link rel="stylesheet" type="text/css" href="Media/css/personnalSpace.css">
</head>

<body>
    <header>
        <section class="sec_logo" onclick="window.location.href='index.php'">
            <img src="Media/img/logo.png" alt="logo">
        </section>
        <nav>
            <?= $header ?> <!-- Affiche le header correspondant au type d'utilisateur -->
        </nav>
        <div class="hamburger">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
    </header>
    <main id="main">
        <!-- Partie information utilisateur -->
        <aside class="info_agent">
            <section class="aside">
                <div class="div_pp">
                    <img class="previewImage"  src="<?= check_file_existe($_SESSION['user']->Id_Personnel, "personnel") ?>" alt="pp"> <!-- Verifie si une photo de profile existe pour cette utilisateur et l'affiche sinon affiche une photo par default-->
                </div>
                <div class="info">
                    <h2>Identifiant <?= $_SESSION["user"]->Id_Personnel ?></h2>
                    <h2><?= $_SESSION["user"]->Id_Type_Personnel ?> <?= $_SESSION["user"]->Nom ?>
                        <?= $_SESSION["user"]->Prenom ?></h2>
                    <button type="button" class="sign2" onclick="openProfil()">Voir son profil</button>
                </div>
            </section>
            <div class="fleche"><svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="50.000000pt"
                    height="50.000000pt" viewBox="0 0 50.000000 50.000000" preserveAspectRatio="xMidYMid meet">

                    <g transform="translate(0.000000,50.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                        <path d="M155 456 c-60 -28 -87 -56 -114 -116 -36 -79 -19 -183 42 -249 33
-36 115 -71 167 -71 52 0 134 35 167 71 34 37 63 110 63 159 0 52 -35 134 -71
167 -37 34 -110 63 -159 63 -27 0 -65 -10 -95 -24z m180 -15 c128 -58 164
-223 72 -328 -101 -115 -283 -88 -348 52 -79 171 104 354 276 276z" />
                        <path d="M160 295 l-44 -45 46 -47 c26 -26 51 -44 54 -40 4 4 -8 23 -26 42
l-34 35 112 0 c68 0 112 4 112 10 0 6 -44 10 -112 10 l-112 0 32 33 c18 18 32
36 32 40 0 16 -18 4 -60 -38z" />
                    </g>
                </svg>
            </div>
        </aside>
        <section class="tableau_recap">
            <section class="grid">
                <?= $panel ?> <!-- Affiche le panel correspondant au type d'utilisateur -->
            </section>
        </section>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="Media/js/nav.js"></script>
    <script src="Media/js/function.js"></script>
    <script>
        // Génération de code HTML pour voir le profile de l'utilisateur
        function openProfil(){
            let main = document.getElementById('main');

            let main_section = document.createElement('section');
            main_section.className = 'main_content_connect';

            let svgElement = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            svgElement.setAttribute("version", "1.1");
            svgElement.setAttribute("id", "Capa_1");
            svgElement.setAttribute("xmlns", "http://www.w3.org/2000/svg");
            svgElement.setAttribute("xmlns:xlink", "http://www.w3.org/1999/xlink");
            svgElement.setAttribute("x", "0px");
            svgElement.setAttribute("y", "0px");
            svgElement.setAttribute("viewBox", "0 0 212.982 212.982");
            svgElement.setAttribute("style", "enable-background:new 0 0 212.982 212.982;");
            svgElement.setAttribute("xml:space", "preserve");
            svgElement.setAttribute("class", "svg_croix");
            svgElement.addEventListener("click", function() {
                window.location.href = "personnalSpace.php";
            });
            let gElement = document.createElementNS("http://www.w3.org/2000/svg", "g");
            gElement.setAttribute("id", "Close");

            let pathElement = document.createElementNS("http://www.w3.org/2000/svg", "path");
            pathElement.setAttribute("style", "fill-rule:evenodd;clip-rule:evenodd;");
            pathElement.setAttribute("d", "M131.804,106.491l75.936-75.936c6.99-6.99,6.99-18.323,0-25.312c-6.99-6.99-18.322-6.99-25.312,0l-75.937,75.937L30.554,5.242c-6.99-6.99-18.322-6.99-25.312,0c-6.989,6.99-6.989,18.323,0,25.312l75.937,75.936L5.242,182.427c-6.989,6.99-6.989,18.323,0,25.312c6.99,6.99,18.322,6.99,25.312,0l75.937-75.937l75.937,75.937c6.989,6.99,18.322,6.99,25.312,0c6.99-6.99,6.99-18.322,0-25.312L131.804,106.491z");

            gElement.appendChild(pathElement);
            svgElement.appendChild(gElement);

            let section = document.createElement('form');
            section.className = 'content_connect';
            section.action = '';
            section.method = 'post';
            section.enctype = "multipart/form-data"


            let formSection = document.createElement('section');
            formSection.className = 'content_connect_form';

            let form = document.createElement('section');
            form.className = "sect_form"

            let h1 = document.createElement('h1');
            h1.textContent = 'Voici votre profil';

            let infoDiv = document.createElement('div');
            infoDiv.className = 'info';

            let idLabel = document.createElement('h2');
            idLabel.textContent = 'Identifiant <?= $_SESSION["user"]->Id_Personnel ?>';
            idLabel.id = 'idLabel';

            let hiddenDiv = document.createElement('div');
            hiddenDiv.id = "nom_prenom_input";

            let nameLabel = document.createElement('h2');
            nameLabel.textContent = '<?= $_SESSION["user"]->Id_Type_Personnel ?> <?= $_SESSION["user"]->Nom ?> <?= $_SESSION["user"]->Prenom ?>';
            nameLabel.id = 'nameLabel';

            hiddenDiv.appendChild(nameLabel);

            let sexeLabel = document.createElement('h2');
            sexeLabel.textContent = 'Sexe : <?= $_SESSION["user"]->checkSexe() ?>';
            sexeLabel.id = 'sexeLabel';

            let dobLabel = document.createElement('h2');
            dobLabel.textContent = 'Né(e) le : <?= formatDate($_SESSION["user"]->Dob) ?>';
            dobLabel.id = 'dobLabel';

            let professionLabel = document.createElement('h2');
            professionLabel.textContent = 'Profession : <?= $_SESSION["user"]->Id_Type_Personnel ?>';
            professionLabel.id = 'professionLabel';

            let adresseLabel = document.createElement('h2');
            adresseLabel.textContent = 'Adresse : <?= $_SESSION["user"]->Adresse ?>';
            adresseLabel.id = 'adresseLabel';

            let cpLabel = document.createElement('h2');
            cpLabel.textContent = 'Code postal : <?= $_SESSION["user"]->Cp ?>';
            cpLabel.id = 'cpLabel';

            let villeLabel = document.createElement('h2');
            villeLabel.textContent = 'Ville : <?= $_SESSION["user"]->Ville ?>';
            villeLabel.id = 'villeLabel';

            infoDiv.appendChild(idLabel);
            infoDiv.appendChild(hiddenDiv);
            infoDiv.appendChild(sexeLabel);
            infoDiv.appendChild(dobLabel);
            infoDiv.appendChild(professionLabel);
            infoDiv.appendChild(adresseLabel);
            infoDiv.appendChild(cpLabel);
            infoDiv.appendChild(villeLabel);

            let button = document.createElement('button');
            button.type = 'button';
            button.textContent = 'Modifier son profil';
            button.className = 'sign2';
            button.id = 'button_updt';
            button.addEventListener('click', updateProfil);

            form.appendChild(h1);
            form.appendChild(infoDiv);
            form.appendChild(button);

            formSection.appendChild(form);

            section.appendChild(formSection);

            let backgroundSection = document.createElement('section');
            backgroundSection.className = 'background1';
            backgroundSection.id = 'backgroundSection';

            let img = document.createElement('img');
            img.src = '<?= check_file_existe($_SESSION['user']->Id_Personnel, "personnel") ?>';
            img.id = 'previewImage';

            backgroundSection.appendChild(img);

            section.appendChild(backgroundSection);
            section.appendChild(svgElement);
            main_section.appendChild(section);
            main.appendChild(main_section);
        }

        // La fonction permet de modifier l'element contenant les informations de la personne en modifiant celle-ci pour pouvoir modifier les informations
        function updateProfil(){
            let idLabel = document.getElementById('idLabel');
            idLabel.remove();

            let nameLabel = document.getElementById('nameLabel');
            nameLabel.textContent = "Nom :";
            let inputNameLabel = document.createElement('input');
            inputNameLabel.type = "text";
            inputNameLabel.name = "name";
            inputNameLabel.id = "name";
            inputNameLabel.maxlength = "50";
            inputNameLabel.value = "<?= $_SESSION["user"]->Nom ?>"
            nameLabel.appendChild(inputNameLabel);

            let prenomLabel = document.createElement('h2');
            prenomLabel.textContent = "Prénom :";
            prenomLabel.id = 'prenomLabel';
            let inputPrenomLabel = document.createElement('input');
            inputPrenomLabel.type = "text";
            inputPrenomLabel.name = "prenom";
            inputPrenomLabel.id = "prenom";
            inputPrenomLabel.maxlength = "50";
            inputPrenomLabel.value = "<?= $_SESSION["user"]->Prenom ?>"
            prenomLabel.appendChild(inputPrenomLabel);
            document.getElementById("nom_prenom_input").appendChild(prenomLabel);

            let sexeLabel = document.getElementById('sexeLabel');
            sexeLabel.remove();

            let dobLabel = document.getElementById('dobLabel');
            dobLabel.textContent = "Né(e) le :";
            let inputDobLabel = document.createElement('input');
            inputDobLabel.type = "date";
            inputDobLabel.name = "dob";
            inputDobLabel.id = "dob";
            inputDobLabel.value = "<?= $_SESSION["user"]->Dob ?>"
            dobLabel.appendChild(inputDobLabel);

            let professionLabel = document.getElementById('professionLabel');
            professionLabel.remove();

            let adresseLabel = document.getElementById('adresseLabel');
            adresseLabel.textContent = "Adresse :";
            let inputAdresseLabel = document.createElement('input');
            inputAdresseLabel.type = "text";
            inputAdresseLabel.name = "adresse";
            inputAdresseLabel.id = "adresse";
            inputAdresseLabel.value = "<?= $_SESSION["user"]->Adresse ?>"
            inputAdresseLabel.maxlength = "150";
            adresseLabel.appendChild(inputAdresseLabel);

            let cpLabel = document.getElementById('cpLabel');
            cpLabel.textContent = "Code postal :";
            let inputCpLabel = document.createElement('input');
            inputCpLabel.type = "text";
            inputCpLabel.name = "cp";
            inputCpLabel.id = "cp";
            inputCpLabel.value = "<?= $_SESSION["user"]->Cp ?>"
            inputCpLabel.maxlength = "6";
            inputCpLabel.pattern = "[0-9]+"
            cpLabel.appendChild(inputCpLabel);

            let villeLabel = document.getElementById('villeLabel');
            villeLabel.textContent = "Ville :";
            let inputVilleLabel = document.createElement('input');
            inputVilleLabel.type = "text";
            inputVilleLabel.name = "ville";
            inputVilleLabel.id = "text";
            inputVilleLabel.value = "<?= $_SESSION["user"]->Ville ?>"
            inputVilleLabel.maxlength = "20";
            villeLabel.appendChild(inputVilleLabel);

            let backgroundSection = document.getElementById('backgroundSection');            
            let previewImage = document.getElementById('previewImage');            
            previewImage.remove();

            let div_pp = document.createElement('div');
            div_pp.className = 'div_pp';

            let uploadFormDiv = document.createElement('div');
            uploadFormDiv.id = 'uploadForm';
            uploadFormDiv.className = 'uploadForm';

            let inputElement = document.createElement('input');
            inputElement.type = 'file';
            inputElement.id = 'imageInput';
            inputElement.accept = 'image/png';
            inputElement.name = 'image';
            inputElement.style.display = 'none';
            inputElement.addEventListener('change', function() {
                previewImages('previewImage', 'imageInput');
            });

            let labelElement = document.createElement('label');
            labelElement.className = 'labal_pp';
            labelElement.htmlFor = 'imageInput';

            let imgElement = document.createElement('img');
            imgElement.id = 'previewImage';
            imgElement.src = '<?= check_file_existe($_SESSION['user']->Id_Personnel, "personnel") ?>';
            imgElement.alt = 'pp';

            let divPlusElement = document.createElement('div');
            divPlusElement.className = 'div_pp_plus';

            let svgElement = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            svgElement.setAttribute("xmlns", "http://www.w3.org/2000/svg");
            svgElement.setAttribute("xmlns:xlink", "http://www.w3.org/1999/xlink");
            svgElement.setAttribute("viewBox", "0 0 256 256");
            svgElement.setAttribute("width", "50");
            svgElement.setAttribute("height", "50");
            svgElement.setAttribute("fill-rule", "nonzero");

            let gElement = document.createElementNS("http://www.w3.org/2000/svg", "g");
            gElement.setAttribute("fill-rule", "nonzero");
            gElement.setAttribute("stroke", "none");
            gElement.setAttribute("stroke-width", "1");
            gElement.setAttribute("stroke-linecap", "butt");
            gElement.setAttribute("stroke-linejoin", "miter");
            gElement.setAttribute("stroke-miterlimit", "10");
            gElement.setAttribute("stroke-dasharray", "");
            gElement.setAttribute("stroke-dashoffset", "");
            gElement.setAttribute("font-family", "none");
            gElement.setAttribute("font-weight", "none");
            gElement.setAttribute("font-size", "none");
            gElement.setAttribute("text-anchor", "none");
            gElement.setAttribute("style", "mix-blend-mode: normal");

            let innerGElement = document.createElementNS("http://www.w3.org/2000/svg", "g");
            innerGElement.setAttribute("transform", "scale(5.12,5.12)");

            let pathElement = document.createElementNS("http://www.w3.org/2000/svg", "path");
            pathElement.setAttribute("d", "M25,2c-12.6907,0 -23,10.3093 -23,23c0,12.69071 10.3093,23 23,23c12.69071,0 23,-10.30929 23,-23c0,-12.6907 -10.30929,-23 -23,-23zM25,4c11.60982,0 21,9.39018 21,21c0,11.60982 -9.39018,21 -21,21c-11.60982,0 -21,-9.39018 -21,-21c0,-11.60982 9.39018,-21 21,-21zM24,13v11h-11v2h11v11h2v-11h11v-2h-11v-11z");

            innerGElement.appendChild(pathElement);
            gElement.appendChild(innerGElement);
            svgElement.appendChild(gElement);

            divPlusElement.appendChild(svgElement);
            labelElement.appendChild(imgElement);
            labelElement.appendChild(divPlusElement);

            let changeDiv = document.createElement('div');
            changeDiv.className = 'change';

            let h1Element = document.createElement('h1');
            h1Element.textContent = 'CHANGER';

            changeDiv.appendChild(h1Element);
            labelElement.appendChild(changeDiv);

            uploadFormDiv.appendChild(inputElement);
            uploadFormDiv.appendChild(labelElement);

            div_pp.appendChild(uploadFormDiv);

            backgroundSection.appendChild(div_pp);
            let button_updt = document.getElementById('button_updt');
            button_updt.textContent = 'Valider';
            button_updt.removeEventListener('click', updateProfil);

            setTimeout(function() {
                button_updt.type = 'sumbit';
            }, 500)
        }

        // pour slider aside (responsif)
        const fleche = document.querySelector(".fleche");
        const aside = document.querySelector(".info_agent");

        fleche.addEventListener('click', () => {
            aside.classList.toggle("open");
            fleche.classList.toggle("open");
            body.classList.toggle("open");

        });

        <?php 
        ## Afficher le message de confirmation ou d'erreur
            if (isset($_SESSION['log_err'])){
                echo "erro_logs('" . $_SESSION['log_err']['type'] . "', '" . $_SESSION['log_err']['message'] . "')";
                unset($_SESSION['log_err']); ## Supression du message
            }
        ?>
        
    </script>
</body>

</html>