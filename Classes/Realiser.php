<?php
// Représentation d'un Realiser
class Realiser extends TableObject {

    // Constructor using parameter promotion as a shorthand
    public function __construct(
        public int $NoOrdonnance,
        public int $NoOperation,
        public $ResultatNum,
        public $ResultatTemps,
        public $ReultatTexte,
        public $Observations
    ) {
    }

    public function __tostring() {
        return "Opearation $this->NoOperation : Ordonnance $this->NoOrdonnance : Resultats ".$this->ResultatNum.$this->ResultatTemps.$this->ReultatTexte." : Observations : $this->Observations";
    }

}
