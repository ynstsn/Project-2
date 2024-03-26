<?php
    ## Permet de formater la date
    function formatDate(string $date): string{
        $date = date_create($date);
        return date_format($date, 'd/m/Y');
    }

    ## Permet de savoir si l'image existe
    function check_file_existe($id, $file): string{
        if (file_exists("Media/img/$file/$id.png")) {
            return "Media/img/" . $file . "/" . $id . ".png";
        } else {
            return "Media/img/inco.png";
        } 
    }

    ## Permet de retourner le type de résutltats de facon plus claire pour l'utilisateur
    function typeResultat($type): string{
        if ($type == "text"){
            return "Texte";
        } elseif ($type == "time"){
            return "Temps";
        } else {
            return "Numérique";
        } 
    }

    ## Permet d'upload une image sur le serveur selon un nom, un lien et le nom du post
    function upload_pic($id, $link, $name_file){
        if (isset($_FILES[$name_file])) { ## on vérifie si une image a été upload 
            $file = $_FILES[$name_file];
            if ($file['error'] === UPLOAD_ERR_OK) { ## On vérifie si il n'y a pas d'erreur
                $uploadFile = $link . $id . ".png"; ## On reformate le nom         
                if (move_uploaded_file($file['tmp_name'], $uploadFile)) { ## On enregistre l'image dans le bon PATH
                    return true;
                } else {
                    echo "Erreur lors du téléchargement du fichier.";
                }
            } else {
                echo "Une erreur est survenue lors du téléchargement du fichier.";
            }
        } else {
            echo "Aucun fichier n'a été téléchargé.";
        }
    }

