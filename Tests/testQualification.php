<?php
require_once __DIR__ . "/../autoload.php";

function afficheTout(array $lesQualification): void {
    echo "------- Tous les produits :\n";
    foreach ($lesQualification as $tp)
        echo $tp, "\n";
}

echo "<pre>";
$QualificationDAO = new QualificationDAO(MaBD::getInstance());
afficheTout($QualificationDAO->getAll());

echo "------- Nouvelle qualification :\n";
$nouveau = new Qualification( "JSP", "Je sais pas",);
echo "\t==> ", $nouveau, "\n";

echo "------- Le chapitre avec le CodeQualif 'MED' :\n";
echo "\t==> ", $QualificationDAO->getOne('MED'), "\n";

echo "------- Enregistrement du nouveau produit : \n";
$QualificationDAO->insert($nouveau);
echo "\t==> ", $QualificationDAO->getOne($nouveau->CodeQualif), "\n";

echo "------- Modification de $nouveau\n";
$nouveau->NomQualif = "Je sais now";
$QualificationDAO->update($nouveau, $nouveau->CodeQualif);
echo "\t==> ", $QualificationDAO->getOne($nouveau->CodeQualif), "\n";

echo "------- Effacement de $nouveau\n";
$QualificationDAO->delete($nouveau);
