<?php
// Classe pour l'accès à la table Ordonnance
class OrdonnanceDAO extends DAO {

    // Récupération d'un objet Ordonnance dont on donne l'identifiant (supposé fiable)
    public function getOne(int $id): Ordonnance {
        $stmt = $this->pdo->prepare("SELECT * FROM ordonnance WHERE NoOrdonnance = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Ordonnance($row['NoOrdonnance'], $row['DateOrdonnance'], $row['DateRealisation'], $row['InformationsPrelevements'], $row['Clos'], $row['NoDemandeur'], $row['NoClient']);
    }

    // Récupération de tous les objets dans une table
    public function getAll(): array {
        $res = array();
        $stmt = $this->pdo->query("SELECT * FROM ordonnance where clos = 1");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = new Ordonnance($row['NoOrdonnance'], $row['DateOrdonnance'], $row['DateRealisation'], $row['InformationsPrelevements'], $row['Clos'], $row['NoDemandeur'], $row['NoClient']);
        return $res;
    }

    // Récupération de toutes les ordonnances cloturées
    public function getAllClos(): array {
        $res = array();
        $stmt = $this->pdo->query("SELECT * FROM ordonnance where clos = 0");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = new Ordonnance($row['NoOrdonnance'], $row['DateOrdonnance'], $row['DateRealisation'], $row['InformationsPrelevements'], $row['Clos'], $row['NoDemandeur'], $row['NoClient']);
        return $res;
    }

    // Fermeture d'une ordonnance
    public function cloreUneOrdonnace(int $id): void{
        $stmt = $this->pdo->prepare("UPDATE `ordonnance` SET Clos = 0"
            . " WHERE NoOrdonnance = :NoOrdonnance ");
        $res = $stmt->execute(array(
            "NoOrdonnance" => $id
        ));  
    }

    // Sauvegarde de l'objet $obj :
    //     $obj->id == UNKNOWN_ID ==> INSERT
    //     $obj->id != UNKNOWN_ID ==> UPDATE
    public function save(object $obj): int {
        if ($obj->NoOrdonnance == DAO::UNKNOWN_ID) {
            $stmt = $this->pdo->prepare("INSERT INTO `ordonnance` (DateOrdonnance, DateRealisation, InformationsPrelevements, Clos, NoDemandeur, NoClient) VALUES (:DateOrdonnance, :DateRealisation, :InformationsPrelevements, :Clos, :NoDemandeur, :NoClient)");
            $res = $stmt->execute(array(
                "DateOrdonnance" => $obj->DateOrdonnance,
                "DateRealisation" => $obj->DateRealisation,
                "InformationsPrelevements" => ucfirst($obj->InformationsPrelevements),
                "Clos" => $obj->Clos,
                "NoDemandeur" => $obj->NoDemandeur,
                "NoClient" => $obj->NoClient
            ));
            $obj->NoOrdonnance = $this->pdo->lastInsertId();
            if(isset($_FILES['image'])){
                upload_pic($obj->NoOrdonnance, "Media/img/ordonnance/", "image");
            }
        } else {
            $stmt = $this->pdo->prepare("UPDATE `ordonnance` SET DateOrdonnance = :DateOrdonnance, DateRealisation = :DateRealisation, InformationsPrelevements = :InformationsPrelevements, Clos = :Clos, NoDemandeur = :NoDemandeur, NoClient = :NoClient"
                                        . " WHERE NoOrdonnance = :NoOrdonnance ");
            $res = $stmt->execute(array(
                "DateOrdonnance" => $obj->DateOrdonnance,
                "DateRealisation" => $obj->DateRealisation,
                "InformationsPrelevements" => ucfirst($obj->InformationsPrelevements),
                "Clos" => $obj->Clos,
                "NoDemandeur" => $obj->NoDemandeur,
                "NoClient" => $obj->NoClient,
                "NoOrdonnance" => $obj->NoOrdonnance
            ));    
            if(isset($_FILES['image'])){
                upload_pic($obj->NoOrdonnance, "Media/img/ordonnance/", "image");
            }    
        }
        return $res;
    }

    // Effacement de l'objet $obj (DELETE)
    public function delete(object $obj): int {
        $ComporterDAO = new ComporterDAO(MaBD::getInstance());
        $ComporterDAO->deleteAnalyseFromOrdonnance($obj->NoOrdonnance);
        $stmt = $this->pdo->prepare("DELETE FROM ordonnance WHERE NoOrdonnance = ?");
        return $stmt->execute([$obj->NoOrdonnance]);
    }

}