<?php
// Représentation d'un TypePrelevement
class TypePrelevement extends TableObject {

     // Constructor using parameter promotion as a shorthand
     public function __construct(
        public string $CodeTypePrel,
        public string $LibelleTypePrel,
        public string $CodeQualif 
    ) {
    }

    public function __tostring() {
        return "$this->CodeTypePrel : $this->LibelleTypePrel : Qualification $this->CodeQualif";
    }

}
