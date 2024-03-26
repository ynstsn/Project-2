<?php
require_once __DIR__ . "/../autoload.php";

function afficheTout(array $lesUsers): void {
    echo "------- Tous les produits :\n";
    foreach ($lesUsers as $usr)
        echo $usr, "\n";
}

echo "<pre>";
$UserDAO = new UserDAO(MaBD::getInstance());
afficheTout($UserDAO->getAll());

echo "------- Se connecter à un utilisateur :\n";
echo "\t==> Existant : ", $UserDAO->check("chahboum", "Test"), "\n";
echo "\t==> Erreur (doit retourner null) : ", $UserDAO->check("chahboum", "false"), "\n";

echo "------- Nouveau user :\n";
$nouveau = new User( DAO::UNKNOWN_ID, "Chahboune", "Marwane", "1", "150 rue de la grosse", "VilleBelle", "2004-08-07", "1", "26260", "chahboum3");
echo "\t==> ", $nouveau, "\n";

echo "------- Le user avec l'id '1' :\n";
echo "\t==> ", $UserDAO->getOne('1'), "\n";

echo "------- Enregistrement du nouveau user : \n";
$UserDAO->save($nouveau);
echo "\t==> ", $UserDAO->getOne($nouveau->Id_Personnel), "\n";

echo "------- Initialisaion du mot de passe : \n";
$UserDAO->initPassWord($nouveau, "Test2");
echo "------- Se connecter au nouvelle utilisateur avec le nouveau mot de passe :\n";
echo "\t==> Existant : ", $UserDAO->check($nouveau->Pseudo, "Test2"), "\n";

echo "------- Récupérer le sexe en string : \n";
echo "\t==> ", $nouveau->checkSexe(), "\n";

echo "------- Modification de $nouveau\n";
$nouveau->Pseudo = "jeDors";
$UserDAO->save($nouveau);
echo "\t==> ", $UserDAO->getOne($nouveau->Id_Personnel), "\n";


echo "------- Effacement de $nouveau\n";
$UserDAO->delete($nouveau);
