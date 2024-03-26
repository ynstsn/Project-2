<?php
// Représentation d'un TypePersonnel
class TypePersonnel extends TableObject {

     // Constructor using parameter promotion as a shorthand
     public function __construct(
        public int $Id_Type_Personnel,
        public string $typeLibellé
    ) {
    }

    public function __tostring() {
        return "$this->Id_Type_Personnel $this->typeLibellé";
    }

}
