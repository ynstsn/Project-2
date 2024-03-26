<?php
require_once __DIR__ . "/../autoload.php";

function afficheTout(array $lesTypesPersonnel): void {
    echo "------- Tous les produits :\n";
    foreach ($lesTypesPersonnel as $tp)
        echo $tp, "\n";
}

echo "<pre>";
$TypePersonnelDAO = new TypePersonnelDAO(MaBD::getInstance());
afficheTout($TypePersonnelDAO->getAll());

echo "------- Nouveau type de personnel :\n";
$nouveau = new TypePersonnel( DAO::UNKNOWN_ID, "Nouveau_type");
echo "\t==> ", $nouveau, "\n";

echo "------- Le type de personnel avec l'id '1' :\n";
echo "\t==> ", $TypePersonnelDAO->getOne('1'), "\n";

echo "------- Enregistrement du nouveau produit : \n";
$TypePersonnelDAO->save($nouveau);
echo "\t==> ", $TypePersonnelDAO->getOne($nouveau->Id_Type_Personnel), "\n";

echo "------- Modification de $nouveau\n";
$nouveau->typeLibellé = "Nouveau_type_modifié";
$TypePersonnelDAO->save($nouveau);
echo "\t==> ", $TypePersonnelDAO->getOne($nouveau->Id_Type_Personnel), "\n";

echo "------- Effacement de $nouveau\n";
$TypePersonnelDAO->delete($nouveau);
