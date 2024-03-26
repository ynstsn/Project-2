<?php
// Classe pour l'accès à la table Analyse
class AnalyseDAO extends DAO {

    // Récupération d'un objet Analyse dont on donne l'identifiant (supposé fiable)
    public function getOne(int|string $id): Analyse {
        $stmt = $this->pdo->prepare("SELECT * FROM analyses join type_prelevement using(CodeTypePrel) WHERE CodeAnalyse = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Analyse($row['CodeAnalyse'], $row['LibelleAnalyse'], $row['CodeTypePrel'], $row['LibelleTypePrel']);
    }

    // Récupération de tous les objets dans une table
    public function getAll(): array {
        $res = array();
        $stmt = $this->pdo->query("SELECT * FROM analyses join type_prelevement using(CodeTypePrel)");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = new Analyse($row['CodeAnalyse'], $row['LibelleAnalyse'], $row['CodeTypePrel'], $row['LibelleTypePrel']);
        return $res;
    }

    // Récupération de toutes les analyses d'une ordonnance
    public function getAllObjectAnalyseFromOrdonnance(int $NoOrdonnance): array {
        $res = [];
        $stmt = $this->pdo->prepare("SELECT * FROM comporter join analyses using(CodeAnalyse) where NoOrdonnance = ?");
        $stmt->execute(array($NoOrdonnance));
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = new Analyse($row['CodeAnalyse'], $row['LibelleAnalyse'], $row['CodeTypePrel']);
        return $res;
    }

    public function save(object $obj): int {
        return null;
    }

    // Modifie une analyse
    public function update(object $obj, string $id_update): int {
        $stmt = $this->pdo->prepare("UPDATE `analyses` SET CodeAnalyse = :NewCodeAnalyse, CodeTypePrel = :CodeTypePrel, LibelleAnalyse = :LibelleAnalyse"
                                    . " WHERE CodeAnalyse = :CodeAnalyse");
        $res = $stmt->execute(array(
            "NewCodeAnalyse" => $obj->CodeAnalyse,
            "CodeAnalyse" => $id_update,
            "LibelleAnalyse" => $obj->LibelleAnalyse,
            "CodeTypePrel" => $obj->CodeTypePrel
        ));        
        
        return $res;
    }

    // Insert une analyse
    public function insert(object $obj): int {
        $stmt = $this->pdo->prepare("INSERT INTO `analyses` (CodeAnalyse, LibelleAnalyse, CodeTypePrel) VALUES (:CodeAnalyse, :LibelleAnalyse, :CodeTypePrel)");
        $res = $stmt->execute(array(
            "CodeAnalyse" => $obj->CodeAnalyse,
            "LibelleAnalyse" => $obj->LibelleAnalyse,
            "CodeTypePrel" => $obj->CodeTypePrel
        ));            
       
        return $res;
    }

    // Effacement de l'objet $obj (DELETE)
    public function delete(object $obj): int {
        $stmt = $this->pdo->prepare("DELETE FROM analyses WHERE CodeAnalyse = ?");
        return $stmt->execute([$obj->CodeAnalyse]);
    }

}