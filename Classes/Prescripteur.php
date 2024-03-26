<?php
// Représentation d'un User
class Prescripteur extends TableObject {

     // Constructor using parameter promotion as a shorthand
     public function __construct(
        public int $NoDemandeur,
        public string $Typedemandeur,
        public string $NomDemandeur,
        public string $AdresseDemandeur,
        public string $CodePostalDemandeur,
        public string $VilleDemandeur
    ) {
    }

    public function __tostring() {
        return "$this->NoDemandeur : $this->Typedemandeur $this->NomDemandeur : $this->AdresseDemandeur, $this->CodePostalDemandeur $this->VilleDemandeur";
    }

}
