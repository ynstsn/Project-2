<?php
// Classe pour l'accès à la table Qualification
class QualificationDAO extends DAO {

    // Récupération d'un objet Qualification dont on donne l'identifiant (supposé fiable)
    public function getOne(int|string $code): Qualification {
        $stmt = $this->pdo->prepare("SELECT * FROM qualification WHERE CodeQualif = ?");
        $stmt->execute([$code]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Qualification($row['CodeQualif'], $row['NomQualif']);
    }

    // Récupération de tous les objets dans une table
    public function getAll(): array {
        $res = array();
        $stmt = $this->pdo->query("SELECT * FROM qualification");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = new Qualification($row['CodeQualif'], $row['NomQualif']);
        return $res;
    }
    
    // Récupération d'un libellé d'une qualification a partir d'un code
    public function getOneByString(string $name): string {
        $stmt = $this->pdo->prepare("SELECT NomQualif FROM qualification WHERE CodeQualif = ?");
        $stmt->execute([$name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['NomQualif'];
    }

    public function save(object $obj): int {
        return null;
    }

    // Modification d'une qualification
    public function update(object $obj, string $id_update): int {
        $stmt = $this->pdo->prepare("UPDATE `qualification` SET NomQualif = :NomQualif, CodeQualif = :NewCodeQualif"
                                    . " WHERE CodeQualif = :CodeQualif");
        $res = $stmt->execute(array(
            "NewCodeQualif" => $obj->CodeQualif,
            "NomQualif" => $obj->NomQualif,
            "CodeQualif" => $id_update
        ));        
        
        return $res;
    }

    // Insertion d'une qualification
    public function insert(object $obj): int {
        $stmt = $this->pdo->prepare("INSERT INTO `qualification` (CodeQualif, NomQualif) VALUES (:CodeQualif, :NomQualif)");
        $res = $stmt->execute(array(
            "CodeQualif" => $obj->CodeQualif,
            "NomQualif" => $obj->NomQualif
        ));            
       
        return $res;
    }

    // Effacement de l'objet $obj (DELETE)
    public function delete(object $obj): int {
        $stmt = $this->pdo->prepare("DELETE FROM qualification WHERE CodeQualif = ?");
        return $stmt->execute([$obj->CodeQualif]);
    }

}