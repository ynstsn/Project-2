<?php
// Classe pour l'accès à la table Comporter
class ComporterDAO extends DAO {

    // Récupération d'un objet Comporter dont on donne l'identifiant (supposé fiable)
    public function getOne(int $id): Comporter {
        return null;
    }

    // Récupération de tous les objets dans une table
    public function getAll(): array {
        $res = array();
        $stmt = $this->pdo->query("SELECT * FROM comporter");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = new Comporter($row['CodeAnalyse'], $row['NoOrdonnance']);
        return $res;
    }

    // Récupération de toutes les analyses comporté d'une ordonnance (retourne un tableau conteneant seulement les codes)
    public function getAllAnalyseFromOrdonnance(int $NoOrdonnance): array {
        $res = [];
        $stmt = $this->pdo->prepare("SELECT * FROM comporter where NoOrdonnance = ?");
        $stmt->execute(array($NoOrdonnance));
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = $row['CodeAnalyse'];
        return $res;
    }

    // Insertion de l'analyse pour l'ordonnance 
    public function save(object $obj): int {
        $stmt = $this->pdo->prepare("INSERT INTO `comporter` (CodeAnalyse, NoOrdonnance) VALUES (:CodeAnalyse, :NoOrdonnance)");
        $res = $stmt->execute(array(
            "CodeAnalyse" => $obj->CodeAnalyse,
            "NoOrdonnance" => $obj->NoOrdonnance
        ));            
       
        return $res;    
    }

    // Effacement de l'objet $obj (DELETE)
    public function delete(object $obj): int {
        $stmt = $this->pdo->prepare("DELETE FROM comporter WHERE CodeAnalyse = ? and NoOrdonnance = ?");
        return $stmt->execute([$obj->CodeAnalyse, $obj->NoOrdonnance]);
    }

    // Effacement toutes les analyses d'une ordoannance
    public function deleteAnalyseFromOrdonnance(int $id): int {
        $stmt = $this->pdo->prepare("DELETE FROM comporter WHERE NoOrdonnance = ?");
        return $stmt->execute([$id]);
    }

}