<?php
require_once __DIR__ . "/../autoload.php";

function afficheTout(array $lesTypePrelevements): void {
    echo "------- Tous les produits :\n";
    foreach ($lesTypePrelevements as $tp)
        echo $tp, "\n";
}

echo "<pre>";
$TypePrelevementDAO = new TypePrelevementDAO(MaBD::getInstance());
afficheTout($TypePrelevementDAO->getAll());

echo "------- Nouveau type de personnel :\n";
$nouveau = new TypePrelevement( "JSP", "Je sais pas", "MED");
echo "\t==> ", $nouveau, "\n";

echo "------- Le type de personnel avec le code 'PI' :\n";
echo "\t==> ", $TypePrelevementDAO->getOne('PI'), "\n";

echo "------- Enregistrement du nouveau produit : \n";
$TypePrelevementDAO->insert($nouveau);
echo "\t==> ", $TypePrelevementDAO->getOne($nouveau->CodeTypePrel), "\n";

echo "------- Modification de $nouveau\n";
$nouveau->LibelleTypePrel = "Je sais now";
$TypePrelevementDAO->update($nouveau);
echo "\t==> ", $TypePrelevementDAO->getOne($nouveau->CodeTypePrel), "\n";

echo "------- Effacement de $nouveau\n";
$TypePrelevementDAO->delete($nouveau);
