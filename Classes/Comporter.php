<?php
// Représentation d'un Comporter
class Comporter extends TableObject {

    // Constructor using parameter promotion as a shorthand
    public function __construct(
        public string $CodeAnalyse,
        public int $NoOrdonnance
    ) {
    }

    public function __tostring() {
        return "$this->NoOrdonnance : contient l'analyse $this->CodeAnalyse";
    }

}
