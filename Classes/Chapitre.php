<?php
// Représentation d'un Chapitre
class Chapitre extends TableObject {

    // Constructor using parameter promotion as a shorthand
    public function __construct(
        public string $Lettre,
        public string $Libelle
    ) {
    }

    public function __tostring() {
        return "$this->Lettre : $this->Libelle";
    }

}
