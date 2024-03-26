<?php
    require_once __DIR__ . "/autoload.php"; 
    session_start();

    if (!isset($_SESSION["user"]) || $_SESSION["user"]->Id_Type_Personnel == "Administrateur") { ## Verificaion si la personne peut ou pas regarder la page sinon direction page de connexion
        header("Location: index.php");
        die();
    } 

    if (!isset($_GET["id"])) { ## Si l'id de la page n'est pas trouvé on renvoie vers la page d'ordonnance
        header("Location: gestionOrdonnance.php");
        die();
    } 

    $OrdonnanceDAO = new OrdonnanceDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les ordonnances
    $uneOrdonnance = $OrdonnanceDAO->getOne($_GET["id"]); ## On récupère les informations de l'ordonnance
    $AnalyseDAO = new AnalyseDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les analyses
    $uneOrdonnance->ListeAnalyse = $AnalyseDAO->getAllObjectAnalyseFromOrdonnance($uneOrdonnance->NoOrdonnance); ## on récupère les analysees de l'ordonnance
    $PatientDAO = new PatientDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les patients
    $lePatient = $PatientDAO->getOne($uneOrdonnance->NoClient); ## On récupère les informations du patient
    $PrescripteurDAO = new PrescripteurDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les prescripteurs
    $lePrescripteur = $PrescripteurDAO->getOne($uneOrdonnance->NoDemandeur); ## On récupère les informations du prescripteur


    ## On vérifie dans quelle cas on utlise la page (si c'est c'est pour voir les resultats, acquérir les resultats, cloturer le dossier ...)
    if(isset($_POST['acquis'])){
        $openAcquis = true;
        $RealiserDAO = new RealiserDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les réalisations
        $lesResultats = $RealiserDAO->getAllResultastForOrdonnace($uneOrdonnance->NoOrdonnance); ## On récupère les résultats des opérations pour l'ordonnace
    } elseif(isset($_POST['viewResultats'])){
        $openViewResultats = true;
        $RealiserDAO = new RealiserDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les réalisations
        $lesResultats = $RealiserDAO->getAllResultastForOrdonnace($uneOrdonnance->NoOrdonnance); ## On récupère les résultats des opérations pour l'ordonnace
    } elseif(isset($_POST['clore'])){
        $OrdonnanceDAO->cloreUneOrdonnace($uneOrdonnance->NoOrdonnance); ## Fermeture du dossier
        header("Location: gestionOrdonnance.php");
        die();
    }

    if (isset($_POST['acquisUpdate'])) {
        $RealiserDAO = new RealiserDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les réalisations
        foreach ($_POST as $key => $operation) { ## Pour chaque opération on réalise le traiitement suivant
            if (!empty($operation['1'])) { ## On  vérifie si la valeur entrée n'est pas vide
                $valeur['text'] = null; ## On initialise un tableau avec les valeurs text, int et time a null pour les donner a l'objet réaliser de la BDD
                $valeur['int'] = null;
                $valeur['time'] = null;
                if($operation['2'] == "int"){ ## On verifie si le type de résultats de l'opration est un entier
                    $valeur['int'] = $operation['1']; ## Alors on initialise la valeur int avec la valeur de l'opération 
                } elseif($operation['2'] == "time"){ ## Sinon si on verifie si le type de résultats de l'opration est un temps
                    $valeur['time'] = $operation['1']; ## Alors on initialise la valeur time avec la valeur de l'opération 
                } else{
                    $valeur['text'] = $operation['1']; ## Sinon on initialise la valeur final avec la valeur de l'opération
                }
                ## On crée l'opbjet de type Realiser avec les informations donnée 
                $nouveau = new Realiser($_GET["id"], $operation[0], $valeur['int'], $valeur['time'], $valeur['text'], null);
                ## On essaye de l'insert si elle n'existe pas sinon on la modifie
                try {
                    $RealiserDAO->insert($nouveau);
                } catch (Exception $e) {
                    $RealiserDAO->update($nouveau);
                }
            }
            
        }
    }    

    $header = createHeader($_SESSION["user"]->Id_Type_Personnel); ## On initialise le header pour l'utilisateur selon son grade
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Visionner Ordonnance · Médicalife</title>
    <meta charset="UTF-8">
    <meta name="author" content="Marwanito">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="Media/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="Media/css/style.css">
    <link rel="stylesheet" type="text/css" href="Media/css/visionnerOrdonnance.css">
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
    <main>
        <form action="gestionOrdonnance.php" method="POST" class="action" id="action">
            <button type="submit" id="button_add" class="sign2">Annuler</button>
        </form>
            <div id="a4_container" class="a4_container">
                <div id="container_page" class="container_page">
                    <div class="info_header">
                        <img src="Media/img/logo.png" alt="logo">
                        <div class="info_labo">
                            <h1>Laboratoire d'analyses médicales</h1>
                            <h1>Médicalife Valence</h1>
                            <p>26 Boulevard Vauban, 26000 Valence</p>
                            <p>Tél. <span class="bold">0484489520</span> - Fax. <span class="bold">0425647364</span></p>
                            <p>Mail : contact.pro@medicalife.fr</p>
                        </div>
                    </div>
                    <div>
                        <div class="info_large">
                            <div class="info_labo">
                                <p>Patient : <span class="bold"><?= strtoupper($lePatient->NomClient) ?> <?= $lePatient->PrenomClient ?></span></p>
                                <p>Adresse : <?= $lePatient->Adresse ?>, <?= $lePatient->CodePostal ?> <?= $lePatient->Ville ?></p>
                                <p>Né(e) le : <?= formatDate($lePatient->DateNaissance) ?></p>
                                <p>Sexe : <?= $lePatient->checkSexe() ?></p>
                            </div>       
                            <div class="info_labo">
                                <p>Prescripteur : <span class="bold"><?= $lePrescripteur->NomDemandeur ?></span></p>
                                <p>Type : <?= $lePrescripteur->Typedemandeur ?></p>
                                <p>Adresse : <?= $lePrescripteur->AdresseDemandeur ?>, <?= $lePrescripteur->CodePostalDemandeur ?> <?= $lePrescripteur->VilleDemandeur ?></p>
                            </div>
                        </div>
                        <div class="info_ordonnance">
                            <div class="info_labo">
                                <p>Dossier n° : <?= $uneOrdonnance->NoOrdonnance ?></p>
                                <p>Prélevé le : <?= formatDate($uneOrdonnance->DateRealisation) ?></p>
                                <p>Enregistré le : <?= formatDate($uneOrdonnance->DateOrdonnance) ?></p>
                            </div>       
                        </div>
                    </div>
                    <?php if(isset($openViewResultats)): ?>
                        <div class="p_avg">
                            <div>
                                <p>Valeur</p>
                                <p>Moyenne</p>
                            </div>
                        </div>
                    <?php endif ?>
                    <form action="?id=<?= $_GET["id"] ?>" id='form_analyses' method="POST">
                        <div class="analyses">
                            <?php foreach($uneOrdonnance->ListeAnalyse as $analyse): ?> <!-- On affiiche chaque analyse de l'ordonnance -->
                                <?php $OperationDAO = new OperationDAO(MaBD::getInstance()); ?> <!-- Initalisation de la classe dao pour les opérations -->
                                <h2><?= $analyse->LibelleAnalyse ?></h2> <!-- On affiiche le nom de l'analyse -->
                                <?php foreach($OperationDAO->getAllOperationFromAnalyse($analyse->CodeAnalyse) as $operation): ?> <!-- On affiiche chaque opérations pour une analyse -->
                                    <div class="ligneOperation">
                                        <h3><?= $operation->LibelleOpe ?></h3> <!-- On affiiche le nom de l'operation -->
                                        <?php if(isset($openAcquis) || isset($openViewResultats)): ?> <!-- Si on est dans le cas de l'acquisiation ou l'affichage de résultats -->
                                            <?php $type = $operation->checkType($operation->TypeResultat); ?> <!-- On récupère le type de résultats de la opération -->
                                            <div class="div_resultats">
                                                <?php if(isset($openAcquis)): ?> <!-- On affiche les elements pour ecrire les resultats -->
                                                    <input type="hidden" name="<?= $operation->NoOperation ?>[]" value="<?= $operation->NoOperation ?>">
                                                    <input type="<?= $type ?>" name="<?= $operation->NoOperation ?>[]" <?php if(isset($lesResultats[$operation->NoOperation])){$type = $operation->checkTypeBDD(); echo "value='". $lesResultats[$operation->NoOperation]->$type ."'";} ?>> <!-- On entre les résultats si l'opérations est deja enregistrer pour les modifier si besoin -->
                                                    <input type="hidden" name="<?= $operation->NoOperation ?>[]" value="<?= $operation->TypeResultat ?>">
                                                    <p><?= $operation->Unite?></p>
                                                <?php else: ?>
                                                    <?php $value = ""; ?>
                                                    <!-- On vérifie si le résultats de l'opération existe alors une enregistre le type et la valeur -->
                                                    <?php if(isset($lesResultats[$operation->NoOperation])){$type = $operation->checkTypeBDD(); $value = $lesResultats[$operation->NoOperation]->$type;} ?>
                                                    <div class="div_value">
                                                        <!-- si elle n'est pas vide alors on l'affiche et en gras si elle est supérieur ou inférieur au norme -->
                                                        <?php if(!empty($value)): ?>
                                                            <p <?php if($value < $operation->NormeInf || $value > $operation->NormeSup){echo "class='bold'";}?>><?= $value ?></p>  
                                                        <?php else: ?> <!-- Sinon-->
                                                            <p>Non enregistrée</p>  
                                                        <?php endif ?>
                                                        <p><?= $operation->Unite?></p>
                                                    </div>
                                                    <div class="div_avg">
                                                        <p><?= $operation->NormeInf?></p>
                                                        <p><?= $operation->NormeSup?></p>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                <?php endforeach ?>
                            <?php endforeach ?>
                        </div>
                        <div class="div_btn" id="div_btn">
                            <?php if(isset($openAcquis)): ?> <!-- Si on est dans le cas de l'acquisition des résultats -->
                                <button id="" class="sign3" onclick="window.location.href='visionnerOrdonnance.php'">Retour</button>
                                <input type="hidden" name="acquisUpdate">
                                <button class="sign3" type="button"
                                    onclick="pop('Validation', 'Voulez-vous valider les résultats ?', 'pop_traitement', 'form_analyses')">Valider</button>
                            <?php elseif(isset($openViewResultats)): ?> <!-- Si on est dans le cas de la vision des résultats -->
                                <button id="" class="sign3" onclick="window.location.href='visionnerOrdonnance.php'">Retour</button>
                                <button id="" class="sign3" onclick="genererPDF()">Éditer des feuilles de travail</button>
                            <?php else: ?> 
                                <?php if($_SESSION["user"]->Id_Type_Personnel == "Opérateur"): ?> <!-- Si l'utilisateur est un opérateur -->
                                    <button id="" class="sign3" onclick="genererPDF()">Éditer des feuilles de travail</button>
                                    <button id="" name="acquis" class="sign3">Acquisition des résultats</button>
                                    <button id="" name="viewResultats" class="sign3">Édition des feuilles de résultats</button>
                                <?php else: ?> <!-- Si l'utilisateur est un personnel administratif -->
                                    <input type="hidden" name="clore">
                                    <button class="sign3" type="button" name="clore"
                                        onclick="pop('Cloture', 'Voulez-vous valider la cloture ?', 'pop_traitement', 'form_analyses')">Clore le dossier</button>
                                <?php endif ?>
                            <?php endif ?>
                        </div>
                    </form>

                </div>
            </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script src="Media/js/nav.js"></script>
    <script src="Media/js/function.js"></script>
    <script>
        // Créer une pdf a partir d'un element HTML
        function genererPDF() {
            let div_btn = document.getElementById('div_btn');
            div_btn.remove();

            // Option par default
            const options = {
                margin: 10,
                filename: 'Ordonnance_<?= $uneOrdonnance->NoOrdonnance ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // Récupérer le contenu de la div
            const contenu = document.getElementById('container_page');

            // Utiliser html2pdf pour générer le PDF
            html2pdf(contenu, options).then(() => {
                location.reload();
            });
            
        }
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