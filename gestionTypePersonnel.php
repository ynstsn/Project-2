<?php
    require_once __DIR__ . "/autoload.php"; 
    $TypePersonnelDAO = new TypePersonnelDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les types de personnels
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
        if(!empty($_POST['nom'])){ ## On verifie si tout les champs sont entrées
            $nouveau = new TypePersonnel( DAO::UNKNOWN_ID, $_POST['nom']);
            $TypePersonnelDAO->save($nouveau); ## On l'enregistre dans la BDD
            $_SESSION['log_err'] = array(
                'type' => "success",
                'message' => "Ajout réalisé avec succès."
            );
            header('Location: gestionTypePersonnel.php');
            die;
        } 
        ## Sinon on affiche un message d'erreur
        $_SESSION['log_err'] = array(
            'type' => "Erreur",
            'message' => "Veuillez remplir tous les champs obligatoires."
        );
    } elseif(isset($_POST['view']) || isset($_POST['update_btn'])){ ## Si on veut modifier
        $unTypePersonnel = $TypePersonnelDAO->getOne($_POST['id']); ## On récupère les information du type dans la BDD
    } elseif(isset($_POST['id_del'])){ ## Si on veut supprimer 
        $unTypePersonnel = $TypePersonnelDAO->getOne($_POST['id_del']);
        $TypePersonnelDAO->delete($unTypePersonnel); ## On supprime le type dans la BDD
        $_SESSION['log_err'] = array(
            'type' => "Erreur",
            'message' => "Suppression réalisée avec succès."
        );
        header('Location: gestionTypePersonnel.php');
        die;
    } elseif(isset($_POST['id_update'])){ ## On veut appliquer la MAJ 
        if(!empty($_POST['nom'])){ ## On verifie si tout les champs sont entrées
            $nouveau = new TypePersonnel($_POST['id_update'], $_POST['nom']);
            $TypePersonnelDAO->save($nouveau); ## On mets a jour les informations dans la BDD
            $_SESSION['log_err'] = array(
                'type' => "success",
                'message' => "Modification réalisée avec succès."
            );
            header('Location: gestionTypePersonnel.php');
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
    <title>Gestion Type Personnel · Médicalife</title>
    <meta charset="UTF-8">
    <meta name="author" content="Marwanito">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="Media/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="Media/css/style.css">
    <link rel="stylesheet" type="text/css" href="Media/css/table.css">
    <link rel="stylesheet" type="text/css" href="Media/css/gestionTypePersonnel.css">

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
            <button type="submit" id="button_add" name="add_btn" class="sign2">Ajouter un type de personnel</button>
            <?php endif ?>
        </form>
        <div class="all_info_tab">
            <form method="POST" action="" id="table" class="table">
                <table id="example" class="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Libellé du type de personnel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- On affiche tout les type de personnel-->
                        <?php foreach($TypePersonnelDAO->getAll() as $typePerso): ?>
                        <tr id="ligne_<?= $typePerso->Id_Type_Personnel ?>" onclick="ouvrirProfile(<?= $typePerso->Id_Type_Personnel ?>)">
                            <td data-label=ID><?= $typePerso->Id_Type_Personnel ?></td>
                            <td data-label="Libellé du type de personnel"><?= $typePerso->typeLibellé ?></td>
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
                            <h2>Libellé :
                            <!-- Si on est dans le cas du visionnage des informations -->
                            <?php if(isset($openView)): ?>
                                <?= $unTypePersonnel->typeLibellé ?>
                            <?php else: ?>
                                <!--Si on veut ajouter ou modifier -->
                                <input type="text" name="nom" id="nom" maxlength="50" <?php if(isset($openUpdate)){echo "value='".$unTypePersonnel->typeLibellé."'";} ?> required> <!-- Si on veut modifier on mets la valeurs deja connu -->
                            <?php endif ?>
                            </h2>
                        </div>
                        <div class="div_btn">
                            <!-- On affiche les buttons selon le cas d'utilisation -->
                            <?php if(isset($openView)): ?> <!-- Pour voire -->
                                <form action="" method="POST" class="form_delet" id="update_personnel">
                                <input type="hidden" name="id" id="id" value="<?= $unTypePersonnel->Id_Type_Personnel ?>">
                                <button type="submit" id="update" name="update_btn" class="sign2">Modifier</button>
                            </form>
                            <form action="" method="POST" class="form_delet" id="delet_personnel">
                                <input type="hidden" name="id_del" id="id_del" value="<?= $unTypePersonnel->Id_Type_Personnel ?>">
                                <button class="sign2" type="button"
                                    onclick="pop('Suppression', 'Voulez-vous valider la suppression ?', 'pop_traitement', 'delet_personnel')">Supprimer</button>
                            </form>
                            <?php elseif(isset($openNew)): ?> <!-- Pour un nouveau -->
                            <input type="hidden" name="add">
                            <button class="sign2" type="button"
                                onclick="pop('Insicrire', 'Voulez-vous valider l\'inscription ?', 'pop_traitement', 'slider')">Valider</button>
                            <?php else: ?> <!-- Pour modifier -->
                            <input type="hidden" name="id_update" id="id_update" value="<?= $unTypePersonnel->Id_Type_Personnel ?>" required>
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

        ## Afficher le message de confirmation ou d'erreur
        if (isset($_SESSION['log_err'])){
            echo "erro_logs('" . $_SESSION['log_err']['type'] . "', '" . $_SESSION['log_err']['message'] . "')";
            unset($_SESSION['log_err']); ## Supression du message
        }
    ?>
    </script>
</body>

</html>