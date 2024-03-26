<?php
// Classe pour l'accès à la table Prescripteur
class PrescripteurDAO extends DAO {

    // Récupération d'un objet Prescripteur dont on donne l'identifiant (supposé fiable)
    public function getOne(int $id): Prescripteur {
        $stmt = $this->pdo->prepare("SELECT * FROM demandeur WHERE NoDemandeur = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Prescripteur($row['NoDemandeur'], $row['Typedemandeur'], $row['NomDemandeur'], $row['AdresseDemandeur'], $row['CodePostalDemandeur'], $row['VilleDemandeur']);
    }

    // Récupération de tous les objets dans une table
    public function getAll(): array {
        $res = array();
        $stmt = $this->pdo->query("SELECT * FROM demandeur");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = new Prescripteur($row['NoDemandeur'], $row['Typedemandeur'], $row['NomDemandeur'], $row['AdresseDemandeur'], $row['CodePostalDemandeur'], $row['VilleDemandeur']);
        return $res;
    }

    // Sauvegarde de l'objet $obj :
    //     $obj->id == UNKNOWN_ID ==> INSERT
    //     $obj->id != UNKNOWN_ID ==> UPDATE
    public function save(object $obj): int {
        if ($obj->NoDemandeur == DAO::UNKNOWN_ID) {
            $stmt = $this->pdo->prepare("INSERT INTO `demandeur` (Typedemandeur, NomDemandeur, AdresseDemandeur, CodePostalDemandeur, VilleDemandeur) VALUES (:Typedemandeur, :NomDemandeur, :AdresseDemandeur, :CodePostalDemandeur, :VilleDemandeur)");
            $res = $stmt->execute(array(
                "Typedemandeur" => ucfirst($obj->Typedemandeur),
                "NomDemandeur" => ucfirst($obj->NomDemandeur),
                "AdresseDemandeur" => $obj->AdresseDemandeur,
                "CodePostalDemandeur" => $obj->CodePostalDemandeur,
                "VilleDemandeur" => ucfirst($obj->VilleDemandeur)
            ));
            $obj->NoDemandeur = $this->pdo->lastInsertId();
            if(isset($_FILES['image'])){
                upload_pic($obj->NoDemandeur, "Media/img/prescripteur/", "image");
            }
        } else {
            $stmt = $this->pdo->prepare("UPDATE `demandeur` SET Typedemandeur = :Typedemandeur, NomDemandeur = :NomDemandeur, AdresseDemandeur = :AdresseDemandeur, CodePostalDemandeur = :CodePostalDemandeur, VilleDemandeur = :VilleDemandeur"
                                        . " WHERE NoDemandeur = :NoDemandeur ");
            $res = $stmt->execute(array(
                "Typedemandeur" => ucfirst($obj->Typedemandeur),
                "NomDemandeur" => ucfirst($obj->NomDemandeur),
                "AdresseDemandeur" => $obj->AdresseDemandeur,
                "CodePostalDemandeur" => $obj->CodePostalDemandeur,
                "VilleDemandeur" => ucfirst($obj->VilleDemandeur),
                "NoDemandeur" => $obj->NoDemandeur
            ));    
            if(isset($_FILES['image'])){
                upload_pic($obj->NoDemandeur, "Media/img/prescripteur/", "image");
            }    
        }
        return $res;
    }

    // Effacement de l'objet $obj (DELETE)
    public function delete(object $obj): int {
        $stmt = $this->pdo->prepare("DELETE FROM demandeur WHERE NoDemandeur = ?");
        return $stmt->execute([$obj->NoDemandeur]);
    }

}