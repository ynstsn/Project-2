<?php
    require_once __DIR__ . "/autoload.php"; 
    $user = new UserDAO(MaBD::getInstance()); ## Initalisation de la classe dao pour les ordonnances
    session_start();

    if (isset($_SESSION["user"]) && !isset($_SESSION['initPassWord'])) { ## On vérifie si l'utilisateur est deja connecté alors on l'envoie sur l'espace personnel 
        header("Location: personnalSpace.php");
        die();
    } 
    
    if(isset($_POST['username']) && isset($_POST['password'])){ ## Si le username et le password est renseigné alors 
        $user = $user->check($_POST['username'], $_POST['password']); ## On verifie si elle correspond a un utilisateur daans la BDD et si le mdp correspond 
        if($user == null){ ## Si il n'existe pas on affiche un message d'erreur
            $_SESSION['log_err'] = array(
                'type' => "error",
                'message' => "Les informations sont incorrectes."
            );
        } elseif(!isset($_SESSION['initPassWord'])){ ## Si il exite et qu'il ne doit pas initailiser son mdp alors on enregistre en session l'utilisateur et on le renvoie sur l'espace personnel
            $_SESSION['user'] = $user;
            header('Location: personnalSpace.php');
            die;
        } else { ## Sinon on le renvoie sur cette page
            $_SESSION['user'] = $user;
            header('Location: index.php');
            die;
        }
    }

    if(isset($_SESSION['initPassWord'])){ ## Si il doit initialiser le mdp alors
        if(isset($_POST['password']) && isset($_POST['password_retype'])){ ## On verifie si il a bien remplie les champs
            if($_POST['password'] === $_POST['password_retype']){ ## On verifie qu'ils soient bien égaux
                $user = $user->initPassWord( $_SESSION['user'], $_POST['password']); ## On mets a jour le mdp dans la BDD
                unset($_SESSION['initPassWord']);
                header('Location: personnalSpace.php');
                die;
            } else {
                $_SESSION['log_err'] = array(
                    'type' => "error",
                    'message' => 'Erreur : Les mots de passes sont différents'
                );
            }
        }
    }


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Connexion · Médicalife</title>
    <meta charset="UTF-8">
    <meta name="author" content="Marwanito">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="Media/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="Media/css/style.css">
    <link rel="stylesheet" type="text/css" href="Media/css/connexion.css">
</head>

<body>
    <header>
        <section class="sec_logo" onclick="window.location.href='index.php'">
            <img src="Media/img/logo.png" alt="logo">
        </section>
    </header>
    <main> 
        <div class="form-container">
            <?php if(isset($_SESSION['initPassWord'])): ?> <!-- Si on est dans le cas de l'initialisation du mdp -->
            <p class="title">Choissisez un nouveau mot de passe</p>
            <form class="form" action="" method="POST">
                <div class="input-group">
                    <label for="username">Mot de passe</label>
                    <input type="password" name="password" id="password" <?php if(isset($_POST['password'])){ echo "value='$_POST[password]'"; } ?> required>
                </div>
                <div class="input-group">
                    <label for="password_retype">Confirmer le mot de passe</label>
                    <input type="password" name="password_retype" id="password_retype" <?php if(isset($_POST['password_retype'])){ echo "value='$_POST[password_retype]'"; } ?> required>
                </div>
                <button class="sign">Valider</button>
            </form>
            <?php else: ?>
            <p class="title">Connexion</p>
            <form class="form" action="" method="POST">
                <div class="input-group">
                    <label for="username">Utilisateur</label>
                    <input type="text" name="username" id="username" <?php if(isset($_POST['username'])){ echo "value='$_POST[username]'"; } ?> required>
                </div>
                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" <?php if(isset($_POST['password'])){ echo "value='$_POST[password]'"; } ?> required>
                </div>
                <button class="sign">Se connecter</button>
            </form>
            <?php endif ?>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="Media/js/nav.js"></script>
    <script src="Media/js/function.js"></script>
    <script>
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