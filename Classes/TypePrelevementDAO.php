<?php
// Classe pour l'accès à la table TypePrelevement
class TypePrelevementDAO extends DAO {

    // Récupération d'un objet TypePrelevement dont on donne l'identifiant (supposé fiable)
    public function getOne(int|string $code): TypePrelevement {
        $stmt = $this->pdo->prepare("SELECT * FROM type_prelevement WHERE CodeTypePrel = ?");
        $stmt->execute([$code]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new TypePrelevement($row['CodeTypePrel'], $row['LibelleTypePrel'], $row['CodeQualif']);
    }

    // Récupération de tous les objets dans une table
    public function getAll(): array {
        $res = array();
        $stmt = $this->pdo->query("SELECT * FROM type_prelevement");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = new TypePrelevement($row['CodeTypePrel'], $row['LibelleTypePrel'], $row['CodeQualif']);
        return $res;
    }
    
    // Récupération d'un libellé d'une qualification a partir d'un libellé
    public function getOneByString(string $name): int {
        $stmt = $this->pdo->prepare("SELECT CodeTypePrel FROM type_prelevement WHERE LibelleTypePrel = ?");
        $stmt->execute([$name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['CodeTypePrel'];
    }

    public function save(object $obj): int {
        return null;
    }

    // Modification d'un type de prelevement
    public function update(object $obj, string $id_update): int {
        $stmt = $this->pdo->prepare("UPDATE `type_prelevement` SET CodeTypePrel = :CodeTypePrelNew, CodeQualif = :CodeQualif, LibelleTypePrel = :LibelleTypePrel"
                                    . " WHERE CodeTypePrel = :CodeTypePrel");
        $res = $stmt->execute(array(
            "CodeTypePrelNew" => $obj->CodeTypePrel,
            "LibelleTypePrel" => $obj->LibelleTypePrel,
            "CodeQualif" => $obj->CodeQualif,
            "CodeTypePrel" => $id_update
        ));        
        
        return $res;
    }

    // Insertion d'un type de prelevement
    public function insert(object $obj): int {
        $stmt = $this->pdo->prepare("INSERT INTO `type_prelevement` (CodeTypePrel, LibelleTypePrel, CodeQualif) VALUES (:CodeTypePrel, :LibelleTypePrel, :CodeQualif)");
        $res = $stmt->execute(array(
            "CodeTypePrel" => $obj->CodeTypePrel,
            "LibelleTypePrel" => $obj->LibelleTypePrel,
            "CodeQualif" => $obj->CodeQualif
        ));            
       
        return $res;
    }

    // Effacement de l'objet $obj (DELETE)
    public function delete(object $obj): int {
        $stmt = $this->pdo->prepare("DELETE FROM type_prelevement WHERE CodeTypePrel = ?");
        return $stmt->execute([$obj->CodeTypePrel]);
    }

}