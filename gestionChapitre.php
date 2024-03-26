<?php
    require_once __DIR__ . "/autoload.php"; 
    $ChapitreDAO = new ChapitreDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les chapitres
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
        if(!empty($_POST['lettre']) && !empty($_POST['nom'])){ ## On verifie si tout les champs sont entrées
            $nouveau = new Chapitre( $_POST['lettre'], $_POST['nom']);
            $ChapitreDAO->insert($nouveau); ## On l'enregistre dans la BDD
            $_SESSION['log_err'] = array(
                'type' => "success",
                'message' => "Ajout réalisé avec succès."
            );
            header('Location: gestionChapitre.php');
            die;
        }
        ## Sinon on affiche un message d'erreur
        $_SESSION['log_err'] = array(
            'type' => "Erreur",
            'message' => "Veuillez remplir tous les champs obligatoires."
        );
    } elseif(isset($_POST['view']) || isset($_POST['update_btn'])){ ## Si on veut modifier
        $unChapitre = $ChapitreDAO->getOne($_POST['id']); ## On récupère les information de l'opération dans la BDD
    } elseif(isset($_POST['id_del'])){ ## Si on veut supprimer 
        $unChapitre = $ChapitreDAO->getOne($_POST['id_del']);
        $ChapitreDAO->delete($unChapitre); ## On supprime le type dans la BDD
        $_SESSION['log_err'] = array(
            'type' => "Erreur",
            'message' => "Suppression réalisée avec succès."
        );
        header('Location: gestionChapitre.php');
        die;
    } elseif(isset($_POST['id_update'])){ ## On veut appliquer la MAJ 
        if(!empty($_POST['lettre']) && !empty($_POST['nom'])){
            $nouveau = new Chapitre($_POST['lettre'], $_POST['nom']);
            $ChapitreDAO->update($nouveau, $_POST['id_update']); ## On mets a jour les informations dans la BDD
            $_SESSION['log_err'] = array(
                'type' => "success",
                'message' => "Modification réalisée avec succès."
            );
            header('Location: gestionChapitre.php');
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
    <title>Gestion Chapitre · Médicalife</title>
    <meta charset="UTF-8">
    <meta name="author" content="Marwanito">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="Media/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="Media/css/style.css">
    <link rel="stylesheet" type="text/css" href="Media/css/table.css">
    <link rel="stylesheet" type="text/css" href="Media/css/gestionChapitre.css">

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
            <button type="submit" id="button_add" name="add_btn" class="sign2">Ajouter un chapitre</button>
            <?php endif ?>
        </form>
        <div class="all_info_tab">
            <form method="POST" action="" id="table" class="table">
                <table id="example" class="dataTable">
                    <thead>
                        <tr>
                            <th>Lettre</th>
                            <th>Libellé du chapitre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- On affiche toutes les chapitres -->
                        <?php foreach($ChapitreDAO->getAll() as $chapitre): ?>
                        <tr id="ligne_<?= $chapitre->Lettre ?>" onclick="ouvrirProfile('<?= $chapitre->Lettre ?>')">
                            <td data-label=Lettre><?= $chapitre->Lettre ?></td>
                            <td data-label="Libellé du chapitre"><?= $chapitre->Libelle ?></td>
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
                        <h2>Lettre :
                        <!-- Si on est dans le cas du visionnage des informations -->
                        <?php if(isset($openView)): ?>
                            <!--Si on veut ajouter ou modifier -->
                            <?= $unChapitre->Lettre ?>
                        <?php else: ?>
                            <input type="text" name="lettre" id="lettre" maxlength="50" <?php if(isset($openUpdate)){echo "value='".$unChapitre->Lettre."'";} ?> required>  <!-- Si on veut modifier on mets la valeurs deja connu -->
                        <?php endif ?>
                        </h2>
                        <h2>Libellé :
                        <?php if(isset($openView)): ?>
                            <?= $unChapitre->Libelle ?>
                        <?php else: ?>
                            <input type="text" name="nom" id="nom" maxlength="50" <?php if(isset($openUpdate)){echo "value='".$unChapitre->Libelle."'";} ?> required>
                        <?php endif ?>
                        </h2>
                    </div>
                    <div class="div_btn">
                        <!-- On affiche les buttons selon le cas d'utilisation -->
                        <?php if(isset($openView)): ?> <!-- Pour voire -->
                            <form action="" method="POST" class="form_delet" id="update_personnel">
                            <input type="hidden" name="id" id="id" value="<?= $unChapitre->Lettre ?>">
                            <button type="submit" id="update" name="update_btn" class="sign2">Modifier</button>
                        </form>
                        <form action="" method="POST" class="form_delet" id="delet_personnel">
                            <input type="hidden" name="id_del" id="id_del" value="<?= $unChapitre->Lettre ?>">
                            <button class="sign2" type="button"
                                onclick="pop('Suppression', 'Voulez-vous valider la suppression ?', 'pop_traitement', 'delet_personnel')">Supprimer</button>
                        </form>
                        <?php elseif(isset($openNew)): ?> <!-- Pour un nouveau -->
                        <input type="hidden" name="add">
                        <button class="sign2" type="button"
                            onclick="pop('Insicrire', 'Voulez-vous valider l\'inscription ?', 'pop_traitement', 'slider')">Valider</button>
                        <?php else: ?> <!-- Pour modifier -->
                        <input type="hidden" name="id_update" id="id_update" value="<?= $unChapitre->Lettre ?>" required>
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
        ## Afficher le message de confirmation ou d'erreur
        if (isset($_SESSION['log_err'])){
            echo "erro_logs('" . $_SESSION['log_err']['type'] . "', '" . $_SESSION['log_err']['message'] . "')";
            unset($_SESSION['log_err']);
        }
    ?>
    </script>
</body>

</html>