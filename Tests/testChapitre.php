<?php
require_once __DIR__ . "/../autoload.php";

function afficheTout(array $lesChapitres): void {
    echo "------- Tous les produits :\n";
    foreach ($lesChapitres as $tp)
        echo $tp, "\n";
}

echo "<pre>";
$ChapitreDAO = new ChapitreDAO(MaBD::getInstance());
afficheTout($ChapitreDAO->getAll());

echo "------- Nouveau chapitre :\n";
$nouveau = new Chapitre( "J", "Je sais pas",);
echo "\t==> ", $nouveau, "\n";

echo "------- Le chapitre avec la lettre 'A' :\n";
echo "\t==> ", $ChapitreDAO->getOne('A'), "\n";

echo "------- Enregistrement du nouveau produit : \n";
$ChapitreDAO->insert($nouveau);
echo "\t==> ", $ChapitreDAO->getOne($nouveau->Lettre), "\n";

echo "------- Modification de $nouveau\n";
$nouveau->Libelle = "Je sais now";
$ChapitreDAO->update($nouveau, $nouveau->Lettre);
echo "\t==> ", $ChapitreDAO->getOne($nouveau->Lettre), "\n";

echo "------- Effacement de $nouveau\n";
$ChapitreDAO->delete($nouveau);
