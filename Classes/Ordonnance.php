<?php
// Représentation d'un Ordonnance
class Ordonnance extends TableObject {

     public function __construct(
        public int $NoOrdonnance,
        public string $DateOrdonnance,
        public string $DateRealisation,
        public string $InformationsPrelevements,
        public bool $Clos,
        public int $NoDemandeur,
        public int $NoClient,
        public array $ListeAnalyse = [],
    ) {
    }

    public function __tostring() {
        return "$this->NoOrdonnance : $this->NoDemandeur $this->NoClient : Date enregistrement $this->DateOrdonnance : Réalisé le $this->DateRealisation, $this->InformationsPrelevements : " . $this->checkEtat();
    }

    // Permet de rendre plus lisible l'etat d'une ordonnance
    public function checkEtat(){
        return ($this->Clos == 1) ? "Ouvert" : "Clos";
    }

}
