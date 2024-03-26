<?php
require_once __DIR__ . "/../autoload.php";

function afficheTout(array $lesPatients): void {
    echo "------- Tous les produits :\n";
    foreach ($lesPatients as $patient)
        echo $patient, "\n";
}

echo "<pre>";
$PatientDAO = new PatientDAO(MaBD::getInstance());
afficheTout($PatientDAO->getAll());

echo "------- Nouveau user :\n";
$nouveau = new Patient( DAO::UNKNOWN_ID, "Chahboune", "Marwane", "150 rue de la grosse", "26260", "VilleBelle", "2004-08-07", "1");
echo "\t==> ", $nouveau, "\n";

echo "------- Le user avec l'id '1' :\n";
echo "\t==> ", $PatientDAO->getOne('1'), "\n";

echo "------- Enregistrement du nouveau user : \n";
$PatientDAO->save($nouveau);
echo "\t==> ", $PatientDAO->getOne($nouveau->NoClient), "\n";

echo "------- Récupérer le sexe en string : \n";
echo "\t==> ", $nouveau->checkSexe(), "\n";

echo "------- Modification de $nouveau\n";
$nouveau->NomClient = "jeDors";
$PatientDAO->save($nouveau);
echo "\t==> ", $PatientDAO->getOne($nouveau->NoClient), "\n";


echo "------- Effacement de $nouveau\n";
$PatientDAO->delete($nouveau);
