<?php
// Représentation d'un Qualification
class Qualification extends TableObject {

     // Constructor using parameter promotion as a shorthand
    public function __construct(
        public string $CodeQualif,
        public string $NomQualif
    ) {
    }

    public function __tostring() {
        return "$this->CodeQualif : $this->NomQualif";
    }

}
