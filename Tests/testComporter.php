<?php
require_once __DIR__ . "/../autoload.php";

function afficheTout(array $lescomporter): void {
    foreach ($lescomporter as $tp)
        echo $tp, "\n";
}

echo "<pre>";
$ComporterDAO = new ComporterDAO(MaBD::getInstance());
echo "------- Tous les comporter :\n";
afficheTout($ComporterDAO->getAll());

echo "------- Nouvelle comporter:\n";
$nouveau = new Comporter( "UAJSP", 78, );
echo "\t==> ", $nouveau, "\n";

echo "------- Les analuyses pour l'ordonnace 1 :\n";
afficheTout($ComporterDAO->getAllAnalyseFromOrdonnance(1));

echo "------- Enregistrement de la nouvelle analyse : \n";
$ComporterDAO->save($nouveau);
echo "\t==> $nouveau\n";

echo "------- Effacement de $nouveau\n";
$ComporterDAO->delete($nouveau);
