<?php
    require_once __DIR__ . "/autoload.php"; 
    $OrdonnanceDAO = new OrdonnanceDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les ordonances
    $AnalyseDAO = new AnalyseDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les analyses
    $AnalyseListe = $AnalyseDAO->getAll(); ## Pour faciliter la lecture du code
    session_start();

    if(isset($_POST['clos']) || isset($_GET['clos'])){ ## Pouir savoir quelle type d'ordonnance on veut 
        $OrdonnanceListe = $OrdonnanceDAO->getAllClos(); ## Liste des ordonnances closes 
    } else {
        $OrdonnanceListe = $OrdonnanceDAO->getAll(); ## Liste des ordonnances
    }


    if (!isset($_SESSION["user"]) || $_SESSION["user"]->Id_Type_Personnel == "Administrateur") { ##On vérifie  si l'utilisateur n'est pas connecté sinon on l'envoie sur la page de connexion
        header("Location: index.php");
        die();
    } 

    $header = createHeader($_SESSION["user"]->Id_Type_Personnel); ## On initialise le header pour l'utilisateur selon son grade

    ## Ici $_SESSION['Temp_Asso'] contient les informations en rapport avec l'ordonnance afin de pouvoir la modifier ou l'ajouter 
    if(isset($_POST['id']) || isset($_POST['add_btn']) || isset($_SESSION['Temp_Asso'])){ ## On verifie si on doit ouvrir le slider
        $openSlider = true;
        if(isset($_SESSION['Temp_Asso'])){ ## Si la $_SESSION['Temp_Asso'] existe on initialise la variable Temp_Asso avec la valeur de $_SESSION['Temp_Asso'] puis on supprime la session (pour eviter des bugs)
            $Temp_Asso = $_SESSION['Temp_Asso'];
            unset($_SESSION['Temp_Asso']);
        }
    }

    ## Puis pour quel cas
    if(isset($_POST['add_btn']) || (isset($Temp_Asso) && $Temp_Asso['type'] === "add")){ ## Pour ajouter 
        $openNew = true;
    } elseif(isset($_POST['view'])){ ## Pour voir 
        $openView = true;
    }  elseif(isset($_POST['update_btn']) || (isset($Temp_Asso) && $Temp_Asso['type'] === "modif")){ ## Pour mettre a jour 
        $openUpdate = true;
    } 

    if(isset($_POST['asso_patient']) || isset($_POST['asso_demandeur'])){ ## On vérifie si un boutton a été cliqué (pour associer un patient ou un prescripteur)
        ## On verifie quelle type de cas on réalise (si on veut ajouter ou modifier)
        if(isset($_POST['add'])){
            $_SESSION['Temp_Asso']['type'] = "add";
        }
        if(isset($_POST['modif'])){
            $_SESSION['Temp_Asso']['type'] = "modif";
            $_SESSION['Temp_Asso']['id'] = $_POST['id_update']; 
        }
        ## On met en $_SESSION['Temp_Asso'] (qui est un tableau l'information reçu avec son nom)
        if(isset($_POST['id_patient'])){
            $_SESSION['Temp_Asso']['id_patient'] = $_POST['id_patient']; 
        }
        if(isset($_POST['id_demandeur'])){
            $_SESSION['Temp_Asso']['id_demandeur'] = $_POST['id_demandeur']; 
        }
        if(!empty($_POST['DateOrdonnance'])){
            $_SESSION['Temp_Asso']['DateOrdonnance'] = $_POST['DateOrdonnance'];
        }
        if(!empty($_POST['DateRealisation'])){
            $_SESSION['Temp_Asso']['DateRealisation'] = $_POST['DateRealisation'];
        }
        if(!empty($_POST['listeAnalyse'])){
            $_SESSION['Temp_Asso']['listeAnalyse'] = $_POST['listeAnalyse'];
        }
        if(!empty($_POST['InformationsPrelevements'])){
            $_SESSION['Temp_Asso']['InformationsPrelevements'] = $_POST['InformationsPrelevements'];
        }

        if(isset($_POST['asso_patient'])){ ## Et on redirige vers la page voulu selon le boutton cliqué
            header('Location: dossierPatient.php?asso');
            die;
        } else {
            header('Location: dossierPrescripteur.php?asso');
            die;
        }
       
    } elseif(isset($_POST['add'])){ ## Si on doit ajouter
        if(!empty($_POST['DateOrdonnance']) && !empty($_POST['DateRealisation']) && !empty($_POST['InformationsPrelevements']) && !empty($_POST['id_patient']) && !empty($_POST['id_demandeur'])){ ## On verifie si tout les champs sont entrées
            $persoInfo = explode("/", $_POST['id_patient']); ## Création d'un tableau car la variable contient deux information séparé par un /, exemple : "11/Marwane chahboune"
            $demandeurInfo = explode("/", $_POST['id_demandeur']); ## Création d'un tableau car la variable contient deux information séparé par un /, exemple : "1/Hopital Valence"
            $nouveau = new Ordonnance( DAO::UNKNOWN_ID, $_POST['DateOrdonnance'], $_POST['DateRealisation'], $_POST['InformationsPrelevements'], 1, $demandeurInfo[0], $persoInfo[0]);
            $OrdonnanceDAO->save($nouveau); ## On l'enregistre dans la BDD
            $ComporterDAO = new ComporterDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les comporter
            foreach(json_decode($_POST['listeAnalyse']) as $codeAnalyse){ ## Pour chaque analyse de l'ordonnace
                $ComporterDAO->save(new Comporter($codeAnalyse, $nouveau->NoOrdonnance)); ## On enregistre la valeur
            }
            $_SESSION['log_err'] = array(
                'type' => "success",
                'message' => "Ajout réalisé avec succès."
            );
            header('Location: gestionOrdonnance.php');
            die;
        }
        ## Sinon on affiche un message d'erreur
        $_SESSION['log_err'] = array(
            'type' => "error",
            'message' => "Veuillez remplir tous les champs obligatoires."
        );
    } elseif(isset($_POST['view']) || isset($_POST['update_btn']) || (isset($Temp_Asso) && $Temp_Asso['type'] === "modif")){ ## Si on veut modifier
        if(isset($Temp_Asso) && $Temp_Asso['type'] === "modif"){ 
            $id = $Temp_Asso['id'];
        } else {
            $id = $_POST['id'];
        }
        $uneOrdonnance = $OrdonnanceDAO->getOne($id); ## On récupère les information de l'opération dans la BDD
        $ComporterDAO = new ComporterDAO(MaBD::getInstance()); ## On récupère les information de la table comporter dans la BDD
        $uneOrdonnance->ListeAnalyse = $ComporterDAO->getAllAnalyseFromOrdonnance($id); ## On récupère les information de les analyses que comporte l'ordonnance dans la BDD
    } elseif(isset($_POST['id_del'])){ ## Si on veut supprimer 
        $uneOrdonnance = $OrdonnanceDAO->getOne($_POST['id_del']);
        $OrdonnanceDAO->delete($uneOrdonnance); ## On supprime le type dans la BDD
        $_SESSION['log_err'] = array(
            'type' => "success",
            'message' => "Suppression réalisée avec succès."
        );
        header('Location: gestionOrdonnance.php');
        die;
    } elseif(isset($_POST['id_update'])){ ## On veut appliquer la MAJ 
        if(!empty($_POST['DateOrdonnance']) && !empty($_POST['DateRealisation']) && !empty($_POST['InformationsPrelevements']) && !empty($_POST['id_patient']) && !empty($_POST['id_demandeur'])){ ## On verifie si tout les champs sont entrées
            $persoInfo = explode("/", $_POST['id_patient']);
            $demandeurInfo = explode("/", $_POST['id_demandeur']);
            $nouveau = new Ordonnance( $_POST['id_update'], $_POST['DateOrdonnance'], $_POST['DateRealisation'], $_POST['InformationsPrelevements'], 1, $demandeurInfo[0], $persoInfo[0]);
            $OrdonnanceDAO->save($nouveau); ## On mets a jour les informations dans la BDD
            $ComporterDAO = new ComporterDAO(MaBD::getInstance());
            $ComporterDAO->deleteAnalyseFromOrdonnance($nouveau->NoOrdonnance); ## On supprime les anciennes informations pour les recréer avec les nouvelles valeurs
            foreach(json_decode($_POST['listeAnalyse']) as $codeAnalyse){
                $ComporterDAO->save(new Comporter($codeAnalyse, $nouveau->NoOrdonnance));
            }
            $_SESSION['log_err'] = array(
                'type' => "success",
                'message' => "Modification réalisée avec succès."
            );
            header('Location: gestionOrdonnance.php');
            die;
        }
        ## Sinon on affiche un message d'erreur
        $_SESSION['log_err'] = array(
            'type' => "error",
            'message' => "Veuillez remplir tous les champs obligatoires."
        );
    }
    
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Gestion Ordonnance · Médicalife</title>
    <meta charset="UTF-8">
    <meta name="author" content="Marwanito">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="Media/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="Media/css/style.css">
    <link rel="stylesheet" type="text/css" href="Media/css/table.css">
    <link rel="stylesheet" type="text/css" href="Media/css/gestionOrdonnance.css">
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
        <!-- Tableau  -->
        <form action="gestionOrdonnance.php" method="POST" class="action" id="action">
            <?php if(isset($openSlider)):?>
            <button type="submit" id="button_add" class="sign2">Annuler</button>
            <?php else: ?>
                <?php if ($_SESSION["user"]->Id_Type_Personnel == "Administratif"): ?>
                <button type="submit" id="button_add" name="add_btn" class="sign2">Ajouter une ordonnance</button>
                    <?php if(isset($_POST['clos']) || isset($_GET['clos'])): ?> 
                        <button type="submit" id="button_add" name="" class="sign2">Voir les ordonnances</button>
                    <?php else: ?>
                        <button type="submit" id="button_add" name="clos" class="sign2">Voir les ordonnances cloturées</button>
                    <?php endif ?>
                <?php endif ?>
            <?php endif ?>
        </form>
        <div class="all_info_tab">
            <form method="POST" action="" id="table" class="table">
                <table id="example" class="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date Ordonnance</th>
                            <th>Date Réalisation</th>
                            <th class="longContent">Informations Prélèvements</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- On affiche toutes les ordonnances -->
                        <?php foreach($OrdonnanceListe as $ordonnance): ?>
                        <tr id="ligne_<?= $ordonnance->NoOrdonnance  ?>"
                            onclick="ouvrirProfile(<?= $ordonnance->NoOrdonnance ?>)">
                            <td data-label=ID><?= $ordonnance->NoOrdonnance ?></td>
                            <td data-label="Date Ordonnance"><?= $ordonnance->DateOrdonnance ?></td>
                            <td data-label="Date Realisation"><?= $ordonnance->DateRealisation ?></td>
                            <td data-label="Informations Prelevements"><?= $ordonnance->InformationsPrelevements ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <input type="hidden" name="view">
                <input type="hidden" name="id" id="id">
            </form>
            <?php if(isset($openSlider)):?> <!-- Si le slider doit etre ouvert -->
            <div id="info_slider" class="info_slider">
                <form method="POST" action="" enctype="multipart/form-data" class="slider" id="slider">
                    <div class="info" id="info_client">
                        <!-- Pour chaque informations on respecte le paterne : -->
                        <!-- Si on est dans le cas du visionnage des informations -->
                        <?php if(isset($openView)): ?> 
                        <?php $PatientDAO = new PatientDAO(MaBD::getInstance()); ?> <!-- Initalisation de la classe dao pour les patients -->
                        <?php $leClient = $PatientDAO->getOne($uneOrdonnance->NoClient) ?> <!-- On récupère les information du patient dans la BDD -->
                        <h2>Patient : <?= $leClient->NomClient ?> <?= $leClient->PrenomClient ?></h2>
                        <?php else: ?> <!--Si on veut ajouter ou modifier -->                          
                        <div id="div_asso_btn">
                            <?php if(isset($Temp_Asso) && $Temp_Asso['type'] === "add" && isset($Temp_Asso['id_patient'])): ?> <!-- Si on veut ajouter une ordonnance et qu'un patient a deja été entré -->
                                <?php $persoInfo = explode("/", $Temp_Asso['id_patient']); ?>  <!-- Création d'un tableau car la variable contient deux information séparé par un /, exemple : "11/Marwane chahboune" -->
                                <input type="hidden" name="id_patient" value="<?= $Temp_Asso['id_patient'] ?>">
                                <h2>Patient : <?= $persoInfo[1] ?></h2>
                                <button type="submit" class="sign2" name='asso_patient' formnovalidate>Modifier le
                                    patient</button>
                            <?php else: ?>
                                <?php if(isset($openUpdate)): ?> <!-- Si on veut modifier une ordonnance -->
                                    <?php if(isset($Temp_Asso) && $Temp_Asso['type'] === "modif"): ?> <!-- Si on veut modifier une ordonnance et qu'un patient a deja été entré  -->
                                        <?php $persoInfo = explode("/", $Temp_Asso['id_patient']); ?> <!-- Création d'un tableau car la variable contient deux information séparé par un /, exemple : "11/Marwane chahboune" -->
                                        <input type="hidden" name="id_patient" value="<?= $Temp_Asso['id_patient'] ?>"> 
                                        <h2>Patient : <?= $persoInfo[1] ?></h2>
                                    <?php else: ?> 
                                        <?php $PatientDAO = new PatientDAO(MaBD::getInstance()); ?> <!-- Initalisation de la classe dao pour les patients -->
                                        <?php $leClient = $PatientDAO->getOne($uneOrdonnance->NoClient) ?> <!-- On récupère les information du patient dans la BDD -->
                                        <h2>Patient : <?= $leClient->NomClient ?> <?= $leClient->PrenomClient ?></h2>
                                        <input type="hidden" name="id_patient"
                                            value="<?= $leClient->NoClient ?>/<?= $leClient->NomClient ?> <?= $leClient->PrenomClient ?>">
                                    <?php endif ?>
                                    <button type="submit" class="sign2" name='asso_patient' formnovalidate>Modifier le
                                        patient</button>
                                <?php else: ?> <!-- Autrement on affiche  -->
                                    <h2>Patient :
                                        <button type="submit" class="sign3" name='asso_patient' formnovalidate>Associer un
                                            patient</button>
                                    </h2>
                                <?php endif ?>
                            <?php endif ?>
                        </div>
                        <?php endif ?>
                        <?php if(isset($openView)): ?>
                        <?php $PrescripteurDAO = new PrescripteurDAO(MaBD::getInstance()); ?>
                        <?php $lePrescripteur = $PrescripteurDAO->getOne($uneOrdonnance->NoDemandeur) ?>
                        <h2>Demandeur : <?= $lePrescripteur->NomDemandeur ?></h2>
                        <?php else: ?>
                        <div id="div_asso_btn">
                            <?php if(isset($Temp_Asso) && $Temp_Asso['type'] === "add" && isset($Temp_Asso['id_demandeur'])): ?>
                            <?php $DemandeurInfo = explode("/", $Temp_Asso['id_demandeur']); ?>
                            <input type="hidden" name="id_demandeur" value="<?= $Temp_Asso['id_demandeur'] ?>">
                            <h2>Demandeur : <?= $DemandeurInfo[1] ?></h2>
                            <button type="submit" class="sign2" name='asso_demandeur' formnovalidate>Modifier le
                                prescripteur</button>
                            <?php else: ?>
                            <?php if(isset($openUpdate)): ?>
                            <?php if(isset($Temp_Asso) && $Temp_Asso['type'] === "modif"): ?>
                            <?php $DemandeurInfo = explode("/", $Temp_Asso['id_demandeur']); ?>
                            <input type="hidden" name="id_demandeur" value="<?= $Temp_Asso['id_demandeur'] ?>">
                            <h2>Demandeur : <?= $DemandeurInfo[1] ?></h2>
                            <?php else: ?>
                            <?php $PrescripteurDAO = new PrescripteurDAO(MaBD::getInstance()); ?>
                            <?php $lePrescripteur = $PrescripteurDAO->getOne($uneOrdonnance->NoDemandeur) ?>
                            <h2>Demandeur : <?= $lePrescripteur->NomDemandeur ?></h2>
                            <input type="hidden" name="id_demandeur"
                                value="<?= $lePrescripteur->NoDemandeur ?>/<?= $lePrescripteur->NomDemandeur ?>">
                            <?php endif ?>
                            <button type="submit" class="sign2" name='asso_demandeur' formnovalidate>Modifier le
                                prescripteur</button>
                            <?php else: ?>
                            <h2>Demandeur :
                                <button type="submit" class="sign3" name='asso_demandeur' formnovalidate>Associer un
                                    prescripteur</button>
                            </h2>
                            <?php endif ?>
                            <?php endif ?>
                        </div>
                        <?php endif ?>
                        <h2>Date ordonnance :
                            <?php if(isset($openView)): ?>
                            <?= $uneOrdonnance->DateOrdonnance ?>
                            <?php else: ?>
                            <input type="date" name="DateOrdonnance" id="DateOrdonnance"
                                <?php if(isset($openUpdate)){echo "value='".$uneOrdonnance->DateOrdonnance."'";} ?>
                                <?php if(isset($Temp_Asso['DateOrdonnance'])){echo "value='".$Temp_Asso['DateOrdonnance']."')";}?>
                                required>
                            <?php endif ?>
                        </h2>
                        <h2>Date réalisation :
                            <?php if(isset($openView)): ?>
                            <?= $uneOrdonnance->DateRealisation ?>
                            <?php else: ?>
                            <input type="date" name="DateRealisation" id="DateRealisation"
                                <?php if(isset($openUpdate)){echo "value='".$uneOrdonnance->DateRealisation."'";} ?>
                                <?php if(isset($Temp_Asso['DateRealisation'])){echo "value='".$Temp_Asso['DateRealisation']."')";}?>
                                required>
                            <?php endif ?>
                        </h2>
                        <?php if(!isset($openView)): ?>
                        <h2>Analyses :
                            <button type="button" class="sign3" name="les_analyses" onclick="gererAnalyses()">Gérer les
                                analyses</button>
                        </h2>
                        <?php endif ?>
                        <h2>Informations :
                            <?php if(isset($openView)): ?>
                            <?= $uneOrdonnance->InformationsPrelevements ?>
                            <?php else: ?>
                            <textarea type="text" name="InformationsPrelevements" id="InformationsPrelevements"
                                required><?php if(isset($openUpdate)){echo $uneOrdonnance->InformationsPrelevements;} ?><?php if(isset($Temp_Asso['InformationsPrelevements'])){echo $Temp_Asso['InformationsPrelevements'];}?></textarea>
                            <?php endif ?>
                        </h2>
                        <input type="hidden" name="listeAnalyse" id="listeAnalyse"
                            <?php if(isset($openUpdate)){echo "value='".json_encode($uneOrdonnance->ListeAnalyse)."'";} ?>
                            <?php if(isset($Temp_Asso['listeAnalyse'])){echo "value='$Temp_Asso[listeAnalyse]'";}?>>
                    </div>
                    <div class="div_btn">
                        <!-- On affiche les buttons selon le cas d'utilisation -->
                        <?php if(isset($openView)): ?> <!-- Pour voire -->
                            <button type="button" onclick="window.location.href='visionnerOrdonnance.php?id=<?= $uneOrdonnance->NoOrdonnance ?>'" id="view" name="view_btn" class="sign2">Voir l'ordonnance</button>
                            <input type="hidden" name="id" id="id_update" value="<?= $uneOrdonnance->NoOrdonnance ?>">
                            <?php if ($_SESSION["user"]->Id_Type_Personnel == "Administratif"): ?>
                                <button type="submit" id="update" name="update_btn" class="sign2">Modifier</button>
                                <input type="hidden" name="id_del" id="id_del" value="<?= $uneOrdonnance->NoOrdonnance ?>">
                                <button class="sign2" type="button"
                                    onclick="pop('Suppression', 'Voulez-vous valider la suppression ?', 'pop_traitement', 'slider')">Supprimer</button>
                            <?php endif ?>
                           
                        <?php elseif(isset($openNew)): ?> <!-- Pour un nouveau -->
                        <input type="hidden" name="add">
                        <button class="sign2" type="button"
                            onclick="pop('Insicrire', 'Voulez-vous valider l\'inscription ?', 'pop_traitement', 'slider')">Valider</button>
                        <?php else: ?> <!-- Pour modifier -->
                        <input type="hidden" name="modif">
                        <input type="hidden" name="id_update" id="id_update"
                            <?php if(isset($openUpdate)){echo "value='".$uneOrdonnance->NoOrdonnance."'";} ?> required>
                        <button class="sign2" type="button"
                            onclick="pop('Modification', 'Voulez-vous valider la modification ?', 'pop_traitement', 'slider')">Valider</button>
                        <?php endif ?>
                    </div>

                </form>
            </div>
            <?php endif ?>
        </div>

        <!-- Template pour afficher les types d'analyses existantes afin de les attribuer a une ordonnance -->
        <div class="main_content_analyses" id="main_content_analyses">
            <div class="content_analyses">
                <h1>Choisissez les analyses</h1>
                <div class="content_analyses_box">
                    <label for="search">
                        <input autocomplete="off" placeholder="Cherchez votre analyses" id="search"
                            type="text" onchange="toSearh()">
                        <div class="icon">
                            <svg stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="swap-on">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linejoin="round"
                                    stroke-linecap="round"></path>
                            </svg>
                            <svg stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="swap-off">
                                <path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linejoin="round" stroke-linecap="round">
                                </path>
                            </svg>
                        </div>
                        <button type="button" onclick="clearContent('search')" id="reset" class="close-btn">
                            <svg viewBox="0 0 20 20" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    fill-rule="evenodd"></path>
                            </svg>
                        </button>
                    </label>
                    <div class="content_analyses_choix">
                        <div class="choix" id="choix">

                        </div>
                    </div>

                </div>
                <button type="button" onclick="getAnalyses()" class="sign2">Valider</button>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="Media/js/nav.js"></script>
    <script src="Media/js/tableau.js"></script>
    <script src="Media/js/function.js"></script>
    <script>
    <?php 
        ## Permet d'initialiser ListeAnalyses en JS pour l'utiliser (passer de PHP à JS)
        if(isset($Temp_Asso['listeAnalyse'])){
            echo "ListeAnalyses = " . $Temp_Asso['listeAnalyse'];
        } elseif(isset($uneOrdonnance->ListeAnalyse)) {
            echo "ListeAnalyses = " . json_encode($uneOrdonnance->ListeAnalyse);
        } else {
            echo "ListeAnalyses = []";
        }
    ?>
    // Permet de valider le formulaire pour envoyer par la methode post l'id du type a chercher 
    function ouvrirProfile(id) {
        document.getElementById('id').value = id;
        document.getElementById('table').submit();
    }

    // Permet d'afficher la template pour ajouter des 
    function gererAnalyses() {
        document.getElementById('main_content_analyses').style.display = "flex";
        toSearh() 
    }

    // Permet d'afficher les analyses correspondantes a celle entrée dans la barre de recherche ou toutes si la barre de recherche est vide
    function toSearh() {
        let value = document.getElementById('search').value
        let contentBox = document.getElementById('choix')
        contentBox.innerHTML = '';
        newList = []
        <?php foreach($AnalyseListe as $analyse): ?> // On réalise pour chaque analyses le meme traitement (on vérifie si elles correspondent a celle entée dans la barre de recherche)
        if ("<?=$analyse->LibelleAnalyse?>".toLowerCase().includes(value.toLowerCase())) { // Si elle correspond 
            newList.push("<?=$analyse->CodeAnalyse?>") // On l'ajoute a la liste
            let div = document.createElement('div'); // On créer un element pour l'afficher
            div.onclick = function() {
                addAnalyse("<?=$analyse->CodeAnalyse?>");
            };
            div.id = "<?=$analyse->CodeAnalyse?>";
            if (ListeAnalyses.includes("<?=$analyse->CodeAnalyse?>")) {
                div.classList.toggle("add"); // Si elle est deja dans la liste alors on mets la classe add (qui l'affiche en tant que deja validé)
            }

            let h2 = document.createElement('h2');
            h2.innerText = "<?=$analyse->CodeAnalyse?> - <?=$analyse->LibelleAnalyse?>";

            div.appendChild(h2);
            contentBox.appendChild(div);
        }
        <?php endforeach ?>
        return newList // On renvoie la liste
    }

    // Permet d'ajouter ou retirer une analyse a une ordonnance (de facon temporaire en attendant la veritable validation)
    function addAnalyse(CodeAnalyse) {
        let btnClicked = document.getElementById(CodeAnalyse)
        if (!ListeAnalyses.includes(CodeAnalyse)) {
            ListeAnalyses.push(CodeAnalyse)
            btnClicked.classList.toggle("add");
        } else {
            let indexToRemove = ListeAnalyses.findIndex(analyse => analyse.CodeAnalyse === CodeAnalyse);
            btnClicked.classList.toggle("add");
            ListeAnalyses.splice(indexToRemove, 1);
        }
    }

    // Permet de valider l'attribution des analyses
    function getAnalyses() {
        document.getElementById("listeAnalyse").value = JSON.stringify(ListeAnalyses);
        document.getElementById('main_content_analyses').style.display = "none";
    }

    // Permet de vider un element
    function clearContent(id) {
        document.getElementById(id).value = "";
        toSearh()
    }

    <?php 
        if(isset($openSlider)){
            if(!isset($openNew)){
                ## Permet de mettre en couleur la ligne du tableau selectionnée 
                echo "ligneIsSelected($id);";
            }
        }
        ## Afficher le message de confirmation ou d'erreur
        if (isset($_SESSION['log_err'])){
            echo "erro_logs('" . $_SESSION['log_err']['type'] . "', '" . $_SESSION['log_err']['message'] . "')";
            unset($_SESSION['log_err']); ## Supression du message
        }
    ?>
    </script>
</body>

</html>