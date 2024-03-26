<?php
    require_once __DIR__ . "/autoload.php"; 
    $PrescripteurDAO = new PrescripteurDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les prescripteurs
    session_start();

    if (!isset($_SESSION["user"])) { ## On vérifie  si l'utilisateur n'est pas connecté sinon on l'envoie sur la page de connexion
        header("Location: index.php");
        die();
    } 

    if(isset($_POST['id_demandeur'])){ ## On vérifie  si on a associé une prescripteur a une ordonnace 
        $_SESSION['Temp_Asso']['id_demandeur'] = $_POST['id_demandeur']; ## Si oui on l'enregistre et on l'envoie
        header("Location: gestionOrdonnance.php");
        die;
    }   

    $header = createHeader($_SESSION["user"]->Id_Type_Personnel); ## On initialise le header pour l'utilisateur selon son grade

    if(isset($_POST['id']) || isset($_POST['add_btn'])){ ## On verifie si on doit ouvrir le slider
        $openSlider = true;
    }

    ## Puis pour quel cas
    if(isset($_POST['add_btn'])){ ## Pour ajouter 
        $openNew = true;
    } elseif(isset($_POST['view'])){ ## Pour voir 
        $openView = true;
    }  elseif(isset($_POST['update_btn'])){ ## Pour mettre a jour 
        $openUpdate = true;
    } 

    if(isset($_POST['add'])){ ## Si on doit ajouter
        if(!empty($_POST['Typedemandeur']) && !empty($_POST['NomDemandeur']) && !empty($_POST['AdresseDemandeur']) && !empty($_POST['CodePostalDemandeur']) && !empty($_POST['VilleDemandeur'])){ ## On verifie si tout les champs sont entrées
            $nouveau = new Prescripteur( DAO::UNKNOWN_ID, $_POST['Typedemandeur'], $_POST['NomDemandeur'], $_POST['AdresseDemandeur'], $_POST['CodePostalDemandeur'], $_POST['VilleDemandeur']);
            $PrescripteurDAO->save($nouveau); ## On l'enregistre dans la BDD
            $_SESSION['log_err'] = array(
                'type' => "success",
                'message' => "Ajout réalisé avec succès."
            );
            header('Location: dossierPrescripteur.php');
            die;
        }
        ## Sinon on affiche un message d'erreur
        $_SESSION['log_err'] = array(
            'type' => "error",
            'message' => "Veuillez remplir tous les champs obligatoires."
        );
    } elseif(isset($_POST['view']) || isset($_POST['update_btn'])){ ## Si on veut modifier
        $unPrescripteur = $PrescripteurDAO->getOne($_POST['id']); ## On récupère les information de l'opération dans la BDD
    } elseif(isset($_POST['id_del'])){ ## Si on veut supprimer 
        $unPrescripteur = $PrescripteurDAO->getOne($_POST['id_del']);
        $PrescripteurDAO->delete($unPrescripteur); ## On supprime le type dans la BDD
        $_SESSION['log_err'] = array(
            'type' => "success",
            'message' => "Suppression réalisée avec succès."
        );
        header('Location: dossierPrescripteur.php');
        die;
    } elseif(isset($_POST['id_update'])){ ## On veut appliquer la MAJ 
        if(!empty($_POST['Typedemandeur']) && !empty($_POST['NomDemandeur']) && !empty($_POST['AdresseDemandeur']) && !empty($_POST['CodePostalDemandeur']) && !empty($_POST['VilleDemandeur'])){ ## On verifie si tout les champs sont entrées
            $nouveau = new Prescripteur( $_POST['id_update'], $_POST['Typedemandeur'], $_POST['NomDemandeur'], $_POST['AdresseDemandeur'], $_POST['CodePostalDemandeur'], $_POST['VilleDemandeur']);
            $PrescripteurDAO->save($nouveau); ## On mets a jour les informations dans la BDD
            $_SESSION['log_err'] = array(
                'type' => "success",
                'message' => "Modification réalisée avec succès."
            );
            header('Location: dossierPrescripteur.php');
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
    <title>Gestion Prescripteur · Médicalife</title>
    <meta charset="UTF-8">
    <meta name="author" content="Marwanito">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="Media/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="Media/css/style.css">
    <link rel="stylesheet" type="text/css" href="Media/css/table.css">
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
        <form action="" method="POST" class="action" id="action">
            <?php if(isset($openSlider)):?>
            <button type="submit" id="button_add" class="sign2">Annuler</button>
            <?php else: ?>
            <button type="submit" id="button_add" name="add_btn" class="sign2">Ajouter un prescripteur</button>
            <?php endif ?>
        </form>
        <div class="all_info_tab">
            <form method="POST" action="" id="table" class="table">
                <table id="example" class="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Nom</th>
                            <th>Adresse</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- On affiche toutes les prescripteurs -->
                        <?php foreach($PrescripteurDAO->getAll() as $prescripteur): ?>
                        <tr id="ligne_<?= $prescripteur->NoDemandeur ?>" onclick="ouvrirProfile(<?= $prescripteur->NoDemandeur ?>)">
                            <td data-label=ID><?= $prescripteur->NoDemandeur ?></td>
                            <td data-label=Type><?= $prescripteur->Typedemandeur ?></td>
                            <td data-label=Nom><?= $prescripteur->NomDemandeur ?></td>
                            <td class="adress" data-label=Adresse><?= $prescripteur->AdresseDemandeur ?>, <?= $prescripteur->CodePostalDemandeur ?>
                                <?= $prescripteur->VilleDemandeur ?></td>
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
                        <div class="div_pp">
                            <?php if(isset($openView)): ?>
                                <img id="image_pp" src="<?= check_file_existe($_POST['id'], "prescripteur"); ?>" alt="pp"> <!-- On verifie queelle image afficher -->
                            <?php else: ?>
                                <div id="uploadForm" class="uploadForm">
                                    <input type="file" id="imageInput" accept="image/png" name="image"
                                        style="display: none;" onchange="previewImages('previewImage', 'imageInput')">
                                    <label class="labal_pp" for="imageInput">
                                        <img id="previewImage" class="previewImage" src="<?php if(isset($openUpdate)){echo check_file_existe($_POST['id'], "prescripteur");} else { echo "Media/img/inco.png"; } ?>" alt="pp"> <!-- On verifie queelle image afficher -->
                                        <div class="div_pp_plus">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                viewBox="0,0,256,256" width="50px" height="50px" fill-rule="nonzero">
                                                <g fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt"
                                                    stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray=""
                                                    stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none"
                                                    text-anchor="none" style="mix-blend-mode: normal">
                                                    <g transform="scale(5.12,5.12)">
                                                        <path
                                                            d="M25,2c-12.6907,0 -23,10.3093 -23,23c0,12.69071 10.3093,23 23,23c12.69071,0 23,-10.30929 23,-23c0,-12.6907 -10.30929,-23 -23,-23zM25,4c11.60982,0 21,9.39018 21,21c0,11.60982 -9.39018,21 -21,21c-11.60982,0 -21,-9.39018 -21,-21c0,-11.60982 9.39018,-21 21,-21zM24,13v11h-11v2h11v11h2v-11h11v-2h-11v-11z">
                                                        </path>
                                                    </g>
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="change">
                                            <h1>CHANGER</h1>
                                        </div>
                                    </label>
                                </div>
                            <?php endif ?>
                        </div>
                        <div class="info" id="info_client">
                            <!-- Pour chaque information on respecte le paterne : -->
                            <h2>Type :
                            <!-- Si on est dans le cas du visionnage des informations -->
                            <?php if(isset($openView)): ?>
                                <?= $unPrescripteur->Typedemandeur ?>
                            <?php else: ?>
                                <!--Si on veut ajouter ou modifier -->
                                <select name="Typedemandeur" id="type">
                                    <option value="Entreprise">Entreprise</option>
                                    <option value="Medecin généraliste">Medecin généraliste</option>
                                </select>
                                <?php endif ?>
                            </h2>
                            <h2>Nom :
                            <?php if(isset($openView)): ?>
                                <?= $unPrescripteur->NomDemandeur ?>
                            <?php else: ?>
                                <input type="text" name="NomDemandeur" id="NomDemandeur" maxlength="50" <?php if(isset($openUpdate)){echo "value='".$unPrescripteur->NomDemandeur."'";} ?> required> <!-- Si on veut modifier on met la valeurs deja connu -->
                            <?php endif ?>
                            </h2>
                            <h2>Adresse : 
                                <?php if(isset($openView)): ?>
                                    <?= $unPrescripteur->AdresseDemandeur ?>
                                    <?php else: ?>
                                        <input type="text" name="AdresseDemandeur" id="AdresseDemandeur" maxlength="150" <?php if(isset($openUpdate)){echo "value='".$unPrescripteur->AdresseDemandeur."'";} ?> required>
                                        <?php endif ?>
                                    </h2>
                           <h2>Code postal :
                                <?php if(isset($openView)): ?>
                                    <?= $unPrescripteur->CodePostalDemandeur ?>
                                <?php else: ?>
                                    <input type="text" name="CodePostalDemandeur" id="CodePostalDemandeur" pattern="[0-9]+" maxlength="6" <?php if(isset($openUpdate)){echo "value='".$unPrescripteur->CodePostalDemandeur."'";} ?> required>
                                <?php endif ?>
                           </h2>
                           <h2>Ville :
                                <?php if(isset($openView)): ?>
                                    <?= $unPrescripteur->VilleDemandeur ?>
                                <?php else: ?>
                                    <input type="text" name="VilleDemandeur" id="VilleDemandeur" maxlength="20" <?php if(isset($openUpdate)){echo "value='".$unPrescripteur->VilleDemandeur."'";} ?> required>
                                <?php endif ?>
                           </h2>
                        </div>
                        <div class="div_btn">
                            <!-- On affiche les buttons selon le cas d'utilisation -->
                            <?php if(isset($openView)): ?> <!-- Pour voire -->
                            <?php if(isset($_GET['asso'])): ?> <!-- Si il s'agit d'une redirection pour associer un prescripteur lors de l'ajout ou la modification d'uen ordonnance -->
                            <form action="" method="POST" class="form_delet" id="delet_civil">
                                <input type="hidden" id="id_demandeur" name="id_demandeur" value="<?= $unPrescripteur->NoDemandeur ?>/<?= $unPrescripteur->NomDemandeur ?>">
                                <button class="sign2" type="submit">Associer</button>
                            </form>
                            <?php else: ?>
                            <form action="" method="POST" class="form_delet" id="update_personnel">
                                <input type="hidden" name="id" id="id" value="<?= $unPrescripteur->NoDemandeur ?>">
                                <button type="submit" id="update" name="update_btn" class="sign2">Modifier</button>
                            </form>
                            <form action="" method="POST" class="form_delet" id="delet_personnel">
                                <input type="hidden" name="id_del" id="id_del" value="<?= $unPrescripteur->NoDemandeur ?>">
                                <button class="sign2" type="button"
                                    onclick="pop('Suppression', 'Voulez-vous valider la suppression ?', 'pop_traitement', 'delet_personnel')">Supprimer</button>
                            </form>
                            <?php endif ?>
                            <?php elseif(isset($openNew)): ?> <!-- Pour un nouveau -->
                            <input type="hidden" name="add">
                            <button class="sign2" type="button"
                                onclick="pop('Insicrire', 'Voulez-vous valider l\'inscription ?', 'pop_traitement', 'slider')">Valider</button>
                            <?php else: ?> <!-- Pour modifier -->
                            <input type="hidden" name="id_update" id="id_update" value="<?= $unPrescripteur->NoDemandeur ?>" required>
                            <button class="sign2" type="button"
                                onclick="pop('Modification', 'Voulez-vous valider la modification ?', 'pop_traitement', 'slider')">Valider</button>
                            <?php endif ?>
                        </div>
                </form>
            </div>
            <?php endif ?>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="Media/js/nav.js"></script>
    <script src="Media/js/tableau.js"></script>
    <script src="Media/js/function.js"></script>
    <script>
    // Permet de valider le formulaire pour envoyer par la methode post l'id du type a chercher 
    function ouvrirProfile(id) {
        document.getElementById('id').value = id;
        document.getElementById('table').submit();
    }
    <?php 
        if(isset($openSlider)){
            if(!isset($openNew)){
                ## Permet de mettre en couleur la ligne du tableau selectionnée 
                echo "ligneIsSelected($_POST[id]);";
            }
        }
        if(isset($openUpdate)){
            ## Permet d'exécuter les functions pour préselctionner les valeurs deja connu en BDD lors de la modification
            echo "checkOptionSelected('type', '$unPrescripteur->Typedemandeur', 'value');";
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