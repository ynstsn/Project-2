<?php
// Représentation d'un Operation
class Operation extends TableObject {

     // Constructor using parameter promotion as a shorthand
     public function __construct(
        public int $NoOperation,
        public string $LibelleOpe,
        public string $TypeResultat,
        public float $NormeInf,
        public float $NormeSup,
        public string $Unite,
        public string $Lettre,
        public string $CodeAnalyse,
        public string $LibelleAnalyse = ""
    ) {
    }

    public function __tostring() {
        return "$this->NoOperation : $this->LibelleOpe : Type de résultats $this->TypeResultat : Norme $this->NormeInf < x < $this->NormeSup en $this->Unite : Chapitre $this->Lettre pour une analyse $this->CodeAnalyse";
    }

    // Permet de traduire le type de resultats connu dans la BDD pour le mettre dans un input en HTML
    public function checkType(){
        if($this->TypeResultat == "int"){
            return "number";
        } elseif($this->TypeResultat == "time"){
            return "time";
        } else{
            return "text";
        }
    }
    
    // Permet de traduire le type de resultats connu dans la BDD pour le mettre dans un input en HTML
    public function checkTypeBDD(){
        if($this->TypeResultat == "int"){
            return "ResultatNum";
        } elseif($this->TypeResultat == "time"){
            return "ResultatTemps";
        } else{
            return "ReultatTexte";
        }
    } 
}
