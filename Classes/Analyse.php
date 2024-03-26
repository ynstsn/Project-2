<?php
// Représentation d'un Analyse
class Analyse extends TableObject {

    // Constructor using parameter promotion as a shorthand
     public function __construct(
        public string $CodeAnalyse,
        public string $LibelleAnalyse,
        public string $CodeTypePrel,
        public string $LibelleTypePrel = ""
    ) {
    }

    public function __tostring() {
        return "$this->CodeAnalyse : $this->LibelleAnalyse : Type de prélevement $this->CodeTypePrel : $this->LibelleTypePrel";
    }

}
