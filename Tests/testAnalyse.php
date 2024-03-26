<?php
require_once __DIR__ . "/../autoload.php";

function afficheTout(array $lesAnalyses): void {
    echo "------- Tous les analyses :\n";
    foreach ($lesAnalyses as $tp)
        echo $tp, "\n";
}

echo "<pre>";
$AnalyseDAO = new AnalyseDAO(MaBD::getInstance());
afficheTout($AnalyseDAO->getAll());

echo "------- Nouvelle analyses:\n";
$nouveau = new Analyse( "UAJSP2", "Une analyses je sais pas 2", "PI", "Perfusion intraveineuse");
echo "\t==> ", $nouveau, "\n";

echo "------- L'analyses avec le code 'CONSE' :\n";
echo "\t==> ", $AnalyseDAO->getOne('CONSE'), "\n";

echo "------- Enregistrement de la nouvelle analyse : \n";
$AnalyseDAO->insert($nouveau);
echo "\t==> ", $AnalyseDAO->getOne($nouveau->CodeAnalyse), "\n";

echo "------- Modification de $nouveau\n";
$nouveau->LibelleAnalyse = "Je sais now";
$AnalyseDAO->update($nouveau);
echo "\t==> ", $AnalyseDAO->getOne($nouveau->CodeAnalyse), "\n";

echo "------- Effacement de $nouveau\n";
$AnalyseDAO->delete($nouveau);
