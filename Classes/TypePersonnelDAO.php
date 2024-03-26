<?php
// Classe pour l'accès à la table TypePersonnel
class TypePersonnelDAO extends DAO {

    // Récupération d'un objet TypePersonnel dont on donne l'identifiant (supposé fiable)
    public function getOne(int $id): TypePersonnel {
        $stmt = $this->pdo->prepare("SELECT * FROM type_personnel WHERE Id_Type_Personnel = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new TypePersonnel($row['Id_Type_Personnel'], $row['typeLibellé']);
    }

    // Récupération de tous les objets dans une table
    public function getAll(): array {
        $res = array();
        $stmt = $this->pdo->query("SELECT * FROM type_personnel");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = new TypePersonnel($row['Id_Type_Personnel'], $row['typeLibellé']);
        return $res;
    }
    
    // Récupération d'un libellé d'une qualification a partir d'un libellé
    public function getOneByString(string $name): int {
        $stmt = $this->pdo->prepare("SELECT Id_Type_Personnel FROM type_personnel WHERE typeLibellé = ?");
        $stmt->execute([$name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['Id_Type_Personnel'];
    }

    // Sauvegarde de l'objet $obj :
    //     $obj->id == UNKNOWN_ID ==> INSERT
    //     $obj->id != UNKNOWN_ID ==> UPDATE
    public function save(object $obj): int {
        if ($obj->Id_Type_Personnel == DAO::UNKNOWN_ID) {
            $stmt = $this->pdo->prepare("INSERT INTO `type_personnel` (typeLibellé) VALUES (:typeLibelle)");
            $res = $stmt->execute(array(
                "typeLibelle" => $obj->typeLibellé
            ));            
            $obj->Id_Type_Personnel = $this->pdo->lastInsertId();
        } else {
            $stmt = $this->pdo->prepare("UPDATE `type_personnel` SET typeLibellé = :typeLibelle"
                                        . " WHERE Id_Type_Personnel=:Id_Type_Personnel");
            $res = $stmt->execute(array(
                "typeLibelle" => $obj->typeLibellé,
                "Id_Type_Personnel" => $obj->Id_Type_Personnel
            ));        
        }
        return $res;
    }

    // Effacement de l'objet $obj (DELETE)
    public function delete(object $obj): int {
        $stmt = $this->pdo->prepare("DELETE FROM type_personnel WHERE Id_Type_Personnel = ?");
        return $stmt->execute([$obj->Id_Type_Personnel]);
    }

}