<?php
require_once __DIR__ . "/../autoload.php";

function afficheTout(array $lesOperations): void {
    echo "------- Tous les Operations :\n";
    foreach ($lesOperations as $tp)
        echo $tp, "\n";
}

echo "<pre>";
$OperationDAO = new OperationDAO(MaBD::getInstance());
afficheTout($OperationDAO->getAll());

echo "------- Nouvelle Operations:\n";
$nouveau = new Operation( DAO::UNKNOWN_ID, "Une Operations je sais pas 2", "int", 0, 15, "M/mm3", "A", "HEMO");
echo "\t==> ", $nouveau, "\n";

echo "------- L'Operations avec le code 1 :\n";
echo "\t==> ", $OperationDAO->getOne(1), "\n";

echo "------- Les operations pour de l'analuyses 'UAJSP' :\n";
afficheTout($OperationDAO->getAllOperationFromAnalyse("UAJSP"));

echo "------- Enregistrement de la nouvelle Operation : \n";
$OperationDAO->save($nouveau);
echo "\t==> ", $OperationDAO->getOne($nouveau->NoOperation), "\n";

echo "------- Modification de $nouveau\n";
$nouveau->LibelleOpe = "Je sais now";
$OperationDAO->save ($nouveau);
echo "\t==> ", $OperationDAO->getOne($nouveau->NoOperation), "\n";

echo "------- Effacement de $nouveau\n";
$OperationDAO->delete($nouveau);
