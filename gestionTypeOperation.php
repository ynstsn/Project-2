<?php
    require_once __DIR__ . "/autoload.php"; 
    $OperationDAO = new OperationDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les opérations
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
        if(!empty($_POST['nom']) && !empty($_POST['type_res']) && !empty($_POST['uni']) && !empty($_POST['lettre']) && !empty($_POST['type_analyse'])){ ## On verifie si tout les champs sont entrées
            $nouveau = new Operation(DAO::UNKNOWN_ID, $_POST['nom'], $_POST['type_res'], $_POST['norm_inf'], $_POST['norm_sup'], $_POST['uni'], $_POST['lettre'], $_POST['type_analyse']);
            $OperationDAO->save($nouveau); ## On l'enregistre dans la BDD
            $_SESSION['log_err'] = array(
                'type' => "success",
                'message' => "Ajout réalisé avec succès."
            );
            header('Location: gestionTypeOperation.php');
            die;
        }
        ## Sinon on affiche un message d'erreur
        $_SESSION['log_err'] = array(
            'type' => "Erreur",
            'message' => "Veuillez remplir tous les champs obligatoires."
        );
    } elseif(isset($_POST['view']) || isset($_POST['update_btn'])){ ## Si on veut modifier
        $uneOperation = $OperationDAO->getOne($_POST['id']); ## On récupère les information de l'opération dans la BDD
    } elseif(isset($_POST['id_del'])){ ## Si on veut supprimer 
        $uneOperation = $OperationDAO->getOne($_POST['id_del']);
        $OperationDAO->delete($uneOperation); ## On supprime le type dans la BDD
        $_SESSION['log_err'] = array(
            'type' => "Erreur",
            'message' => "Suppression réalisée avec succès."
        );
        header('Location: gestionTypeOperation.php');
        die;
    } elseif(isset($_POST['id_update'])){ ## On veut appliquer la MAJ 
        if(!empty($_POST['nom']) && !empty($_POST['type_res']) && !empty($_POST['uni']) && !empty($_POST['lettre']) && !empty($_POST['type_analyse'])){ ## On verifie si tout les champs sont entrées
            $nouveau = new Operation($_POST['id_update'], $_POST['nom'], $_POST['type_res'], $_POST['norm_inf'], $_POST['norm_sup'], $_POST['uni'], $_POST['lettre'], $_POST['type_analyse']);
            $OperationDAO->save($nouveau); ## On mets a jour les informations dans la BDD
            $_SESSION['log_err'] = array(
                'type' => "success",
                'message' => "Modification réalisée avec succès."
            );
            header('Location: gestionTypeOperation.php');
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
    <title>Gestion Type Opération · Médicalife</title>
    <meta charset="UTF-8">
    <meta name="author" content="Marwanito">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="Media/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="Media/css/style.css">
    <link rel="stylesheet" type="text/css" href="Media/css/table.css">
    <link rel="stylesheet" type="text/css" href="Media/css/gestionTypeOperation.css">

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
            <button type="submit" id="button_add" name="add_btn" class="sign2">Ajouter un type d'opération</button>
            <?php endif ?>
        </form>
        <div class="all_info_tab">
            <form method="POST" action="" id="table" class="table">
                <table id="example" class="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Libellé</th>
                            <th>Type</th>
                            <th>N.Inf.</th>
                            <th>N.Sup.</th>
                            <th>Unité</th>
                            <th>Chap.</th>
                            <th>Analyse</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- On affiche toutes les opérations-->
                        <?php foreach($OperationDAO->getAll() as $operation): ?>
                        <tr id="ligne_<?= $operation->NoOperation ?>" onclick="ouvrirProfile('<?= $operation->NoOperation ?>')">
                            <td data-label=ID><?= $operation->NoOperation ?></td>
                            <td data-label="Libelle de l'Opération"><?= $operation->LibelleOpe ?></td>
                            <td data-label="Type du résultat"><?= typeResultat($operation->TypeResultat) ?></td>
                            <td data-label="Norme inférieure"><?= $operation->NormeInf ?></td>
                            <td data-label="Norme supérieure"><?= $operation->NormeSup ?></td>
                            <td data-label="Unité"><?= $operation->Unite ?></td>
                            <td data-label=Chapitre><?= $operation->Lettre ?></td>
                            <td data-label="Type du analyse"><?= $operation->CodeAnalyse ?></td>
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
                            <h2>Libellé :
                            <!-- Si on est dans le cas du visionnage des informations -->
                            <?php if(isset($openView)): ?>
                                <?= $uneOperation->LibelleOpe ?>
                            <?php else: ?>
                                <!--Si on veut ajouter ou modifier -->
                                <input type="text" name="nom" id="nom" maxlength="50" <?php if(isset($openUpdate)){echo "value='".$uneOperation->LibelleOpe."'";} ?> required> <!-- Si on veut modifier on mets la valeurs deja connu -->
                            <?php endif ?>
                            </h2>
                            <h2>Type du résultat :
                            <?php if(isset($openView)): ?>
                                <?= typeResultat($uneOperation->TypeResultat) ?>
                            <?php else: ?>
                                <select name="type_res" id="type_res">
                                    <option value="int">Numérique</option>
                                    <option value="text">Texte</option>  
                                    <option value="time">Temps</option>
                                </select>
                            <?php endif ?>
                            </h2>
                            <h2>Norme inférieure :
                            <?php if(isset($openView)): ?>
                                <?= $uneOperation->NormeInf ?>
                            <?php else: ?>
                                <input type="text" name="norm_inf" id="norm_inf" maxlength="50" <?php if(isset($openUpdate)){echo "value='".$uneOperation->NormeInf."'";} ?>>
                            <?php endif ?>
                            </h2>
                            <h2>Norme supérieure :
                            <?php if(isset($openView)): ?>
                                <?= $uneOperation->NormeSup ?>
                            <?php else: ?>
                                <input type="text" name="norm_sup" id="norm_sup" maxlength="50" <?php if(isset($openUpdate)){echo "value='".$uneOperation->NormeSup."'";} ?>>
                            <?php endif ?>
                            </h2>
                            <h2>Unité :
                            <?php if(isset($openView)): ?>
                                <?= $uneOperation->Unite ?>
                            <?php else: ?>
                                <input type="text" name="uni" id="uni" maxlength="50" <?php if(isset($openUpdate)){echo "value='".$uneOperation->Unite."'";} ?> required>
                            <?php endif ?>
                            </h2>
                            <h2>Chapitre :
                            <?php $ChapitreDAO = new ChapitreDAO(MaBD::getInstance()); ?>
                            <?php if(isset($openView)): ?>
                                <?= $ChapitreDAO->getOneByString($uneOperation->Lettre) ?>
                            <?php else: ?>
                                <select name="lettre" id="lettre">
                                    <?php foreach($ChapitreDAO->getAll() as $chapitre): ?>
                                        <option value="<?= $chapitre->Lettre  ?>"><?= $chapitre->Libelle ?></option>
                                    <?php endforeach ?>
                                </select>                            
                                <?php endif ?>
                            </h2>
                            <h2>Analyse :
                            <?php if(isset($openView)): ?>
                                <?= $uneOperation->LibelleAnalyse ?>
                            <?php else: ?>
                                <?php $AnalyseDAO = new AnalyseDAO(MaBD::getInstance()); ?>
                                <select name="type_analyse" id="type_analyse">
                                    <?php foreach($AnalyseDAO->getAll() as $analyse): ?>
                                        <option value="<?= $analyse->CodeAnalyse ?>"><?= $analyse->LibelleAnalyse ?></option>
                                    <?php endforeach ?>
                                </select>
                            <?php endif ?>
                            </h2>
                        </div>
                        <div class="div_btn">
                            <!-- On affiche les buttons selon le cas d'utilisation -->
                            <?php if(isset($openView)): ?> <!-- Pour voire -->
                                <form action="" method="POST" class="form_delet" id="update_personnel">
                                <input type="hidden" name="id" id="id" value="<?= $uneOperation->NoOperation ?>">
                                <button type="submit" id="update" name="update_btn" class="sign2">Modifier</button>
                            </form>
                            <form action="" method="POST" class="form_delet" id="delet_personnel">
                                <input type="hidden" name="id_del" id="id_del" value="<?= $uneOperation->NoOperation ?>">
                                <button class="sign2" type="button"
                                    onclick="pop('Suppression', 'Voulez-vous valider la suppression ?', 'pop_traitement', 'delet_personnel')">Supprimer</button>
                            </form>
                            <?php elseif(isset($openNew)): ?> <!-- Pour un nouveau -->
                            <input type="hidden" name="add">
                            <button class="sign2" type="button"
                                onclick="pop('Insicrire', 'Voulez-vous valider l\'inscription ?', 'pop_traitement', 'slider')">Valider</button>
                            <?php else: ?> <!-- Pour modifier -->
                            <input type="hidden" name="id_update" id="id_update" value="<?= $uneOperation->NoOperation ?>" required>
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
                echo "ligneIsSelected('$_POST[id]');";
            }
        }
        if(isset($openUpdate)){
            ## Permet d'exécuter les functions pour préselctionner les valeurs deja connu en BDD lors de la modification
            echo "checkOptionSelected('lettre', '$uneOperation->Lettre', 'value');";
            echo "checkOptionSelected('type_res', '$uneOperation->TypeResultat', 'value');";
            echo "checkOptionSelected('type_analyse', '$uneOperation->LibelleAnalyse', 'text');";
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