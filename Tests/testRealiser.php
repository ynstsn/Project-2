<?php

require_once __DIR__ . "/../autoload.php";

function afficheTout(array $lesRealisers): void {
    foreach ($lesRealisers as $Realiser)
        echo $Realiser, "\n";
}

echo "<pre>";
$RealiserDAO = new RealiserDAO(MaBD::getInstance());
echo "------- Tous les resulats :\n";
afficheTout($RealiserDAO->getAll());

echo "------- Nouveau Realiser :\n";
$nouveau = new Realiser( 163, 18, 50.00, null, null, null);
echo "\t==> ", $nouveau, "\n";

echo "------- Le Realiser avec l'id '163' et '17' :\n";
echo "\t==> ", $RealiserDAO->getOne([163, 17]), "\n";

echo "------- Le resultatst de 'ordonnace id '163' :\n";
afficheTout($RealiserDAO->getAllResultastForOrdonnace(163));

echo "------- Enregistrement du nouveau Realisers : \n";
$RealiserDAO->insert($nouveau);
echo "\t==> ", $RealiserDAO->getOne([$nouveau->NoOrdonnance, $nouveau->NoOperation]), "\n";

echo "------- Modification de $nouveau\n";
$nouveau->ResultatNum = 25.00;
$RealiserDAO->update($nouveau);
echo "\t==> ", $RealiserDAO->getOne([$nouveau->NoOrdonnance, $nouveau->NoOperation]), "\n";

echo "------- Effacement de $nouveau\n";
$RealiserDAO->delete($nouveau);
