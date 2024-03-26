<?php 
    ## Permet de supprimer la session et de renvoyer sur la page de connexion
    session_start();
    session_destroy(); 
    header('Location:index.php');
    die();
