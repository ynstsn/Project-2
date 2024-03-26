<?php
// Classe pour l'accès à la table Patient
class PatientDAO extends DAO {

    // Récupération d'un objet Patient dont on donne l'identifiant (supposé fiable)
    public function getOne(int $id): Patient {
        $stmt = $this->pdo->prepare("SELECT * FROM client WHERE NoClient = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Patient($row['NoClient'], $row['NomClient'], $row['PrenomClient'], $row['Adresse'], $row['CodePostal'], $row['Ville'], $row['DateNaissance'], $row['Sexe']);
    }

    // Récupération de tous les objets dans une table
    public function getAll(): array {
        $res = array();
        $stmt = $this->pdo->query("SELECT * FROM client");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = new Patient($row['NoClient'], $row['NomClient'], $row['PrenomClient'], $row['Adresse'], $row['CodePostal'], $row['Ville'], $row['DateNaissance'], $row['Sexe']);
        return $res;
    }

    // Sauvegarde de l'objet $obj :
    //     $obj->id == UNKNOWN_ID ==> INSERT
    //     $obj->id != UNKNOWN_ID ==> UPDATE
    public function save(object $obj): int {
        if ($obj->NoClient == DAO::UNKNOWN_ID) {
            $stmt = $this->pdo->prepare("INSERT INTO `client` (NomClient, PrenomClient, Adresse, CodePostal, Ville, DateNaissance, Sexe) VALUES (:NomClient, :PrenomClient, :Adresse, :CodePostal, :Ville, :DateNaissance, :Sexe)");
            $res = $stmt->execute(array(
                "NomClient" => ucfirst($obj->NomClient),
                "PrenomClient" => ucfirst($obj->PrenomClient),
                "Adresse" => $obj->Adresse,
                "CodePostal" => $obj->CodePostal,
                "Ville" => ucfirst($obj->Ville),
                "DateNaissance" => $obj->DateNaissance,
                "Sexe" => $obj->Sexe
            ));
            $obj->NoClient = $this->pdo->lastInsertId();
            if(isset($_FILES['image'])){
                upload_pic($obj->NoClient, "Media/img/client/", "image");
            }
        } else {
            $stmt = $this->pdo->prepare("UPDATE `client` SET NomClient = :NomClient, PrenomClient = :PrenomClient, Adresse = :Adresse, CodePostal = :CodePostal, Ville = :Ville, DateNaissance = :DateNaissance, Sexe = :Sexe"
                                        . " WHERE NoClient = :NoClient ");
            $res = $stmt->execute(array(
                "NomClient" => ucfirst($obj->NomClient),
                "PrenomClient" => ucfirst($obj->PrenomClient),
                "Adresse" => $obj->Adresse,
                "CodePostal" => $obj->CodePostal,
                "Ville" => ucfirst($obj->Ville),
                "DateNaissance" => $obj->DateNaissance,
                "Sexe" => $obj->Sexe,
                "NoClient" => $obj->NoClient
            ));    
            if(isset($_FILES['image'])){
                upload_pic($obj->NoClient, "Media/img/client/", "image");
            }    
        }
        return $res;
    }

    // Effacement de l'objet $obj (DELETE)
    public function delete(object $obj): int {
        $stmt = $this->pdo->prepare("DELETE FROM client WHERE NoClient = ?");
        return $stmt->execute([$obj->NoClient]);
    }

}