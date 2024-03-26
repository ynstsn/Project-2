<?php

require_once __DIR__ . "/../autoload.php";

function afficheTout(array $lesOrdonnances): void {
    echo "------- Tous les produits :\n";
    foreach ($lesOrdonnances as $ordonnance)
        echo $ordonnance, "\n";
}

echo "<pre>";
$OrdonnanceDAO = new OrdonnanceDAO(MaBD::getInstance());
afficheTout($OrdonnanceDAO->getAll());

echo "------- Nouveau ordonnance :\n";
$nouveau = new Ordonnance( DAO::UNKNOWN_ID, date_format(date_create(), "Y/m/d"), "2023-01-10", "Je suis la de fou gros", "1", 1, 1);
echo "\t==> ", $nouveau, "\n";

echo "------- Le ordonnance avec l'id '1' :\n";
echo "\t==> ", $OrdonnanceDAO->getOne('1'), "\n";

echo "------- Enregistrement du nouveau ordonnances : \n";
$OrdonnanceDAO->save($nouveau);
echo "\t==> ", $OrdonnanceDAO->getOne($nouveau->NoOrdonnance), "\n";

echo "------- Modification de $nouveau\n";
$nouveau->InformationsPrelevements = "Je suis la";
$OrdonnanceDAO->save($nouveau);
echo "\t==> ", $OrdonnanceDAO->getOne($nouveau->NoOrdonnance), "\n";

echo "------- Clore une ordonnance : $nouveau\n";
$OrdonnanceDAO->cloreUneOrdonnace($nouveau->NoOrdonnance);
echo "\t==> ", $OrdonnanceDAO->getOne($nouveau->NoOrdonnance), "\n";

echo "------- Effacement de $nouveau\n";
$OrdonnanceDAO->delete($nouveau);
