<?php
// Représentation d'un User
class User extends TableObject {

     // Constructor using parameter promotion as a shorthand
     public function __construct(
        public int $Id_Personnel,
        public string $Nom,
        public string $Prenom,
        public bool $Sexe,
        public string $Adresse,
        public string $Ville,
        public string $Dob,
        public string|int $Id_Type_Personnel,
        public int $Cp,
        public string $Pseudo
    ) {
    }

    public function __tostring() {
        return "$this->Id_Personnel $this->Pseudo : $this->Nom $this->Prenom : ".$this->checkSexe().", $this->Dob : $this->Adresse, $this->Cp $this->Ville : $this->Id_Type_Personnel";
    }

    // Permet de rendre plus lisible le sex d'un patient
    public function checkSexe(){
        return ($this->Sexe == 1) ? "Homme" : "Femme";
    }
}
