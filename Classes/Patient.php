<?php
// Représentation d'un User
class Patient extends TableObject {

     // Constructor using parameter promotion as a shorthand
     public function __construct(
        public int $NoClient,
        public string $NomClient,
        public string $PrenomClient,
        public string $Adresse,
        public string $CodePostal,
        public string $Ville,
        public string $DateNaissance,
        public bool $Sexe
    ) {
    }

    public function __tostring() {
        return "$this->NoClient : $this->NomClient $this->PrenomClient : ".$this->checkSexe().", $this->DateNaissance : $this->Adresse, $this->CodePostal $this->Ville";
    }

    // Permet de rendre plus lisible le sex d'un patient
    public function checkSexe(){
        return ($this->Sexe == 1) ? "Homme" : "Femme";
    }
}
