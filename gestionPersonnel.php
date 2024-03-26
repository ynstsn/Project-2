<?php
    require_once __DIR__ . "/autoload.php"; 
    $userDAO = new UserDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les utilisateurs
    session_start();

    if (!isset($_SESSION["user"])) { ##On vérifie  si l'utilisateur n'est pas connecté sinon on l'envoie sur la page de connexion
        header("Location: index.php");
        die();
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
        if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['sexe']) && isset($_POST['dob']) && isset($_POST['profession']) && isset($_POST['adresse']) && isset($_POST['cp']) && isset($_POST['ville'])){ ## On verifie si tout les champs sont entrées
            $nouveau = new User( DAO::UNKNOWN_ID, $_POST['nom'], $_POST['prenom'], $_POST['sexe'], $_POST['adresse'], $_POST['ville'], $_POST['dob'], $_POST['profession'], $_POST['cp'], $userDAO->findPseudo($_POST['nom'], $_POST['prenom']));
            $userDAO->save($nouveau); ## On l'enregistre dans la BDD
            $_SESSION['log_err'] = array(
                'type' => "success",
                'message' => "Ajout réalisée avec succès."
            );
            header('Location: gestionPersonnel.php');
            die;
        }
        ## Sinon on affiche un message d'erreur
        $_SESSION['log_err'] = array(
            'type' => "Erreur",
            'message' => "Veuillez remplir tous les champs obligatoires."
        );
    } elseif(isset($_POST['view']) || isset($_POST['update_btn'])){ ## Si on veut modifier
        $unUser = $userDAO->getOne($_POST['id']); ## On récupère les information de l'opération dans la BDD
    } elseif(isset($_POST['id_del'])){ ## Si on veut supprimer 
        $unUser = $userDAO->getOne($_POST['id_del']);
        $userDAO->delete($unUser); ## On supprime le type dans la BDD
        $_SESSION['log_err'] = array(
            'type' => "Erreur",
            'message' => "Suppression réalisée avec succès."
        );
        header('Location: gestionPersonnel.php');
        die;
    } elseif(isset($_POST['id_update'])){ ## On veut appliquer la MAJ 
        if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['sexe']) && isset($_POST['dob']) && isset($_POST['profession']) && isset($_POST['adresse']) && isset($_POST['cp']) && isset($_POST['ville'])){ ## On verifie si tout les champs sont entrées
            $nouveau = new User($_POST['id_update'], $_POST['nom'], $_POST['prenom'], $_POST['sexe'], $_POST['adresse'], $_POST['ville'], $_POST['dob'], $_POST['profession'], $_POST['cp'], $userDAO->findPseudo($_POST['nom'], $_POST['prenom']));
            $userDAO->save($nouveau); ## On mets a jour les informations dans la BDD
            $_SESSION['log_err'] = array(
                'type' => "success",
                'message' => "Modification réalisée avec succès."
            );
            header('Location: gestionPersonnel.php');
            die;
        }
        ## Sinon on affiche un message d'erreur
        $_SESSION['log_err'] = array(
            'type' => "Erreur",
            'message' => "Veuillez remplir tous les champs obligatoires."
        );
    }
    
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Gestion Personnel · Médicalife</title>
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
            <button type="submit" id="button_add" name="add_btn" class="sign2">Ajouter un employé</button>
            <?php endif ?>
        </form>
        <div class="all_info_tab">
            <form method="POST" action="" id="table" class="table">
                <table id="example" class="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Sexe</th>
                            <th>Né(e) le</th>
                            <th>Profession</th>
                            <th>Adresse</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- On affiche toutes les personnels -->
                        <?php foreach($userDAO->getAll() as $employe): ?>
                        <tr id="ligne_<?= $employe->Id_Personnel ?>" onclick="ouvrirProfile(<?= $employe->Id_Personnel ?>)">
                            <td data-label=ID><?= $employe->Id_Personnel ?></td>
                            <td data-label=Nom><?= $employe->Nom ?></td>
                            <td data-label=Prénom><?= $employe->Prenom ?></td>
                            <td data-label=Sexe><?= $employe->checkSexe() ?></td>
                            <td data-label="Né(e) le"><?= formatDate($employe->Dob) ?></td>
                            <td data-label=Profession><?= $employe->Id_Type_Personnel  ?></td>
                            <td class="adress" data-label=Adresse><?= $employe->Adresse ?>, <?= $employe->Cp ?>
                                <?= $employe->Ville ?></td>
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
                                <img id="image_pp" src="<?= check_file_existe($_POST['id'], "personnel"); ?>" alt="pp"> <!-- On verifie queelle image afficher -->
                            <?php else: ?>
                                <div id="uploadForm" class="uploadForm">
                                    <input type="file" id="imageInput" accept="image/png" name="image"
                                        style="display: none;" onchange="previewImages('previewImage', 'imageInput')"> <!-- On affiche l'image qui viens d'etre envoyé -->
                                    <label class="labal_pp" for="imageInput">
                                        <img id="previewImage" class="previewImage" src="<?php if(isset($openUpdate)){echo check_file_existe($_POST['id'], "personnel");} else { echo "Media/img/inco.png"; } ?>" alt="pp"> <!-- On verifie queelle image afficher -->
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
                            <!-- Pour chaque informations on respecte le paterne : -->
                            <h2>Nom :
                            <!-- Si on est dans le cas du visionnage des informations -->
                            <?php if(isset($openView)): ?>
                                <?= $unUser->Nom ?>
                            <?php else: ?>
                                <!--Si on veut ajouter ou modifier -->
                                <input type="text" name="nom" id="nom" maxlength="50" <?php if(isset($openUpdate)){echo "value='".$unUser->Nom."'";} ?> required> <!-- Si on veut modifier on mets la valeurs deja connu -->
                            <?php endif ?>
                            </h2>
                            <h2>Prénom :
                            <?php if(isset($openView)): ?>
                                <?= $unUser->Prenom ?>
                            <?php else: ?>
                                <input type="text" name="prenom" id="prenom" maxlength="50" <?php if(isset($openUpdate)){echo "value='".$unUser->Prenom."'";} ?> required>
                            <?php endif ?>
                            </h2>
                            <h2>Sexe :
                            <?php if(isset($openView)): ?>
                                <?= $unUser->checkSexe() ?>
                            <?php else: ?>
                                <select name="sexe" id="sexe">
                                    <option value="1">Homme</option>
                                    <option value="0">Femme</option>
                                </select>
                            <?php endif ?>
                            </h2>
                            <h2>Né(e) le :
                            <?php if(isset($openView)): ?>
                                <?= formatDate($unUser->Dob) ?>
                            <?php else: ?>
                                <input type="date" name="dob" id="dob" maxlength="50" <?php if(isset($openUpdate)){echo "value='".$unUser->Dob."'";} ?> required>
                            <?php endif ?>
                            </h2>
                            <h2>Profession : 
                                <?php if(isset($openView)): ?>
                                    <?= $unUser->Id_Type_Personnel ?>
                                <?php else: ?>
                                    <?php $TypePersonnelDAO = new TypePersonnelDAO(MaBD::getInstance()); ?>
                                    <select name="profession" id="profession">
                                        <?php foreach($TypePersonnelDAO->getAll() as $profession): ?>
                                            <option value="<?= $profession->Id_Type_Personnel ?>"><?= $profession->typeLibellé ?></option>
                                        <?php endforeach ?>
                                    </select>
                                <?php endif ?>
                            </h2>
                            <h2>Adresse : 
                                <?php if(isset($openView)): ?>
                                    <?= $unUser->Adresse ?>
                                <?php else: ?>
                                    <input type="text" name="adresse" id="adresse" maxlength="150" <?php if(isset($openUpdate)){echo "value='".$unUser->Adresse."'";} ?> required>
                                <?php endif ?>
                           </h2>
                           <h2>Code postal :
                                <?php if(isset($openView)): ?>
                                    <?= $unUser->Cp ?>
                                <?php else: ?>
                                    <input type="text" name="cp" id="cp" pattern="[0-9]+" maxlength="6" <?php if(isset($openUpdate)){echo "value='".$unUser->Cp."'";} ?> required>
                                <?php endif ?>
                           </h2>
                           <h2>Ville :
                                <?php if(isset($openView)): ?>
                                    <?= $unUser->Ville ?>
                                <?php else: ?>
                                    <input type="text" name="ville" id="ville" maxlength="20" <?php if(isset($openUpdate)){echo "value='".$unUser->Ville."'";} ?> required>
                                <?php endif ?>
                           </h2>
                        </div>
                        <div class="div_btn">
                            <!-- On affiche les buttons selon le cas d'utilisation -->
                            <?php if(isset($openView)): ?> <!-- Pour voire -->
                                <form action="" method="POST" class="form_delet" id="update_personnel">
                                <input type="hidden" name="id" id="id" value="<?= $unUser->Id_Personnel ?>">
                                <button type="submit" id="update" name="update_btn" class="sign2">Modifier</button>
                            </form>
                            <form action="" method="POST" class="form_delet" id="delet_personnel">
                                <input type="hidden" name="id_del" id="id_del" value="<?= $unUser->Id_Personnel ?>">
                                <button class="sign2" type="button"
                                    onclick="pop('Suppression', 'Voulez-vous valider la suppression ?', 'pop_traitement', 'delet_personnel')">Supprimer</button>
                            </form>
                            <?php elseif(isset($openNew)): ?> <!-- Pour un nouveau -->
                            <input type="hidden" name="add">
                            <button class="sign2" type="button"
                                onclick="pop('Insicrire', 'Voulez-vous valider l\'inscription ?', 'pop_traitement', 'slider')">Valider</button>
                            <?php else: ?> <!-- Pour modifier -->
                            <input type="hidden" name="id_update" id="id_update" value="<?= $unUser->Id_Personnel ?>" required>
                            <input type="hidden" name="modif">
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
            $sexe = ($unUser->Sexe == 1) ? 1 : 0 ;
            echo "checkOptionSelected('sexe', '$sexe', 'value');";
            echo "checkOptionSelected('profession', '$unUser->Id_Type_Personnel', 'text');";
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