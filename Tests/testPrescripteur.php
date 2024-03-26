<?php
require_once __DIR__ . "/../autoload.php";

function afficheTout(array $lesPrescripteurs): void {
    echo "------- Tous les prescripteurs :\n";
    foreach ($lesPrescripteurs as $prescripteur)
        echo $prescripteur, "\n";
}

echo "<pre>";
$PrescripteurDAO = new PrescripteurDAO(MaBD::getInstance());
afficheTout($PrescripteurDAO->getAll());

echo "------- Nouveau prescripteur :\n";
$nouveau = new Prescripteur( DAO::UNKNOWN_ID, "Entreprises", "Hopitale Valence", "150 rue de la grosse", "26260", "VilleBelle");
echo "\t==> ", $nouveau, "\n";

echo "------- Le prescripteur avec l'id '1' :\n";
echo "\t==> ", $PrescripteurDAO->getOne('1'), "\n";

echo "------- Enregistrement du nouveau prescripteur : \n";
$PrescripteurDAO->save($nouveau);
echo "\t==> ", $PrescripteurDAO->getOne($nouveau->NoDemandeur), "\n";

echo "------- Modification de $nouveau\n";
$nouveau->NomDemandeur = "jeDors";
$PrescripteurDAO->save($nouveau);
echo "\t==> ", $PrescripteurDAO->getOne($nouveau->NoDemandeur), "\n";


echo "------- Effacement de $nouveau\n";
$PrescripteurDAO->delete($nouveau);
