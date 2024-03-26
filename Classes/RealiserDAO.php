<?php
// Classe pour l'accès à la table Realiser
class RealiserDAO extends DAO {

    // Récupération d'un objet Realiser dont on donne l'identifiant (supposé fiable)
    public function getOne(int|array $idOr): Realiser {
        $stmt = $this->pdo->prepare("SELECT * FROM réaliser WHERE NoOrdonnance = ? and NoOperation = ?");
        $stmt->execute([$idOr[0], $idOr[1]]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Realiser($row['NoOrdonnance'], $row['NoOperation'], $row['ResultatNum'], $row['ResultatTemps'], $row['ReultatTexte'], $row['Observations']);
    }

    // Récupération de tous les objets dans une table
    public function getAll(): array {
        $res = array();
        $stmt = $this->pdo->query("SELECT * FROM réaliser");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = new Realiser($row['NoOrdonnance'], $row['NoOperation'], $row['ResultatNum'], $row['ResultatTemps'], $row['ReultatTexte'], $row['Observations']);
        return $res;
    }

    // Récupération de tous les résultats d'une ordonnance
    public function getAllResultastForOrdonnace(int $id): array {
        $res = array();
        $stmt = $this->pdo->prepare("SELECT * FROM réaliser where NoOrdonnance = ?");
        $stmt->execute([$id]);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            $res[$row['NoOperation']] = new Realiser($row['NoOrdonnance'], $row['NoOperation'], $row['ResultatNum'], $row['ResultatTemps'], $row['ReultatTexte'], $row['Observations']);
        }

        return $res;
    }

    public function save(object $obj): int {
        return null;
    }

    // Modification d'une réalisation
    public function update(object $obj): int {
        $stmt = $this->pdo->prepare("UPDATE `réaliser` SET ResultatNum = :ResultatNum, ResultatTemps = :ResultatTemps, ReultatTexte = :ReultatTexte, Observations = :Observations"
                                    . " WHERE NoOrdonnance = :NoOrdonnance AND NoOperation = :NoOperation");
        $res = $stmt->execute(array(
            "NoOrdonnance" => $obj->NoOrdonnance,
            "NoOperation" => $obj->NoOperation,
            "ResultatTemps" => $obj->ResultatTemps,
            "ResultatNum" => $obj->ResultatNum,
            "ReultatTexte" => $obj->ReultatTexte,
            "Observations" => $obj->Observations
        ));        
        
        return $res;
    }

    // Insertion d'une réalisation
    public function insert(object $obj): int {
        $stmt = $this->pdo->prepare("INSERT INTO `réaliser` (NoOrdonnance, NoOperation, ResultatNum, ResultatTemps, ReultatTexte, Observations) VALUES (:NoOrdonnance, :NoOperation, :ResultatNum, :ResultatTemps, :ReultatTexte, :Observations)");
        $res = $stmt->execute(array(
            "NoOrdonnance" => $obj->NoOrdonnance,
            "NoOperation" => $obj->NoOperation,
            "ResultatTemps" => $obj->ResultatTemps,
            "ResultatNum" => $obj->ResultatNum,
            "ReultatTexte" => $obj->ReultatTexte,
            "Observations" => $obj->Observations
        ));            
       
        return $res;
    }


    // Effacement de l'objet $obj (DELETE)
    public function delete(object $obj): int {
        $stmt = $this->pdo->prepare("DELETE FROM réaliser WHERE NoOperation = ? and NoOrdonnance = ?");
        return $stmt->execute([$obj->NoOperation, $obj->NoOrdonnance]);
    }


}