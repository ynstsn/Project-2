<?php
// Classe pour l'accès à la table Chapitre
class ChapitreDAO extends DAO {

    // Récupération d'un objet Chapitre dont on donne l'identifiant (supposé fiable)
    public function getOne(int|string $code): Chapitre {
        $stmt = $this->pdo->prepare("SELECT * FROM chapitre WHERE Lettre = ?");
        $stmt->execute([$code]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Chapitre($row['Lettre'], $row['Libelle']);
    }

    // Récupération de tous les objets dans une table
    public function getAll(): array {
        $res = array();
        $stmt = $this->pdo->query("SELECT * FROM chapitre");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = new Chapitre($row['Lettre'], $row['Libelle']);
        return $res;
    }
    
    // Récupération d'un libellé d'un chapitre a partir d'une lettre
    public function getOneByString(string $name): string {
        $stmt = $this->pdo->prepare("SELECT Libelle FROM chapitre WHERE Lettre = ?");
        $stmt->execute([$name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['Libelle'];
    }

    public function save(object $obj): int {
        return null;
    }

    // Modification d'un chapitre
    public function update(object $obj, string $id_update): int {
        $stmt = $this->pdo->prepare("UPDATE `chapitre` SET Libelle = :Libelle, Lettre = :NewLettre"
                                    . " WHERE Lettre = :Lettre");
        $res = $stmt->execute(array(
            "NewLettre" => $obj->Lettre,
            "Libelle" => $obj->Libelle,
            "Lettre" => $id_update
        ));        
        
        return $res;
    }

    // Insertion d'un chapitre
    public function insert(object $obj): int {
        $stmt = $this->pdo->prepare("INSERT INTO `chapitre` (Lettre, Libelle) VALUES (:Lettre, :Libelle)");
        $res = $stmt->execute(array(
            "Lettre" => $obj->Lettre,
            "Libelle" => $obj->Libelle
        ));            
       
        return $res;
    }

    // Effacement de l'objet $obj (DELETE)
    public function delete(object $obj): int {
        $stmt = $this->pdo->prepare("DELETE FROM chapitre WHERE Lettre = ?");
        return $stmt->execute([$obj->Lettre]);
    }

}