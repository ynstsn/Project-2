<?php
// Classe pour l'accès à la table Operation
class OperationDAO extends DAO {

    // Récupération d'un objet Operation dont on donne l'identifiant (supposé fiable)
    public function getOne(int $id): Operation {
        $stmt = $this->pdo->prepare("SELECT * FROM operation join analyses using(CodeAnalyse) WHERE NoOperation = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Operation($row['NoOperation'], $row['LibelleOpe'], $row['TypeResultat'], $row['NormeInf'], $row['NormeSup'], $row['Unite'], $row['Lettre'], $row['CodeAnalyse'], $row['LibelleAnalyse']);
    }

    // Récupération de tous les objets dans une table
    public function getAll(): array {
        $res = array();
        $stmt = $this->pdo->query("SELECT * FROM operation join analyses using(CodeAnalyse)");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = new Operation($row['NoOperation'], $row['LibelleOpe'], $row['TypeResultat'], $row['NormeInf'], $row['NormeSup'], $row['Unite'], $row['Lettre'], $row['CodeAnalyse'], $row['LibelleAnalyse']);
        return $res;
    }

    // Récupération de toutes les opérations d'une analyses
    public function getAllOperationFromAnalyse(string $CodeAnalyse): array {
        $res = [];
        $stmt = $this->pdo->prepare("SELECT * FROM operation join analyses using(CodeAnalyse) where CodeAnalyse = ?");
        $stmt->execute(array($CodeAnalyse));
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = new Operation($row['NoOperation'], $row['LibelleOpe'], $row['TypeResultat'], $row['NormeInf'], $row['NormeSup'], $row['Unite'], $row['Lettre'], $row['CodeAnalyse'], $row['LibelleAnalyse']);
        return $res;
    }

    // Sauvegarde de l'objet $obj :
    //     $obj->id == UNKNOWN_ID ==> INSERT
    //     $obj->id != UNKNOWN_ID ==> UPDATE
    public function save(object $obj): int {
        
        if ($obj->NoOperation == DAO::UNKNOWN_ID) {
            
            $stmt = $this->pdo->prepare("INSERT INTO `operation` (LibelleOpe, TypeResultat, NormeInf, NormeSup, Unite, Lettre, CodeAnalyse) VALUES (:LibelleOpe, :TypeResultat, :NormeInf, :NormeSup, :Unite, :Lettre, :CodeAnalyse)");
            $res = $stmt->execute(array(
                "LibelleOpe" => $obj->LibelleOpe,
                "TypeResultat" => $obj->TypeResultat,
                "NormeInf" => $obj->NormeInf,
                "NormeSup" => $obj->NormeSup,
                "Unite" => $obj->Unite,
                "Lettre" => $obj->Lettre,
                "CodeAnalyse" => $obj->CodeAnalyse
            ));
            
            $obj->NoOperation = $this->pdo->lastInsertId();
        } else {
            $stmt = $this->pdo->prepare("UPDATE `operation` SET LibelleOpe = :LibelleOpe, TypeResultat = :TypeResultat, NormeInf = :NormeInf, NormeSup = :NormeSup, Unite = :Unite, Lettre = :Lettre, CodeAnalyse = :CodeAnalyse"
                                        . " WHERE NoOperation = :NoOperation ");
            $res = $stmt->execute(array(
                "LibelleOpe" => $obj->LibelleOpe,
                "TypeResultat" => $obj->TypeResultat,
                "NormeInf" => $obj->NormeInf,
                "NormeSup" => $obj->NormeSup,
                "Unite" => $obj->Unite,
                "Lettre" => $obj->Lettre,
                "CodeAnalyse" => $obj->CodeAnalyse,
                "NoOperation" => $obj->NoOperation
            ));    
        }
        return $res;
    }
    
    // Effacement de l'objet $obj (DELETE)
    public function delete(object $obj): int {
        $stmt = $this->pdo->prepare("DELETE FROM operation WHERE NoOperation = ?");
        return $stmt->execute([$obj->NoOperation]);
    }

}