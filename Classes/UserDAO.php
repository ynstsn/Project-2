<?php
// Classe pour l'accès à la table User
class UserDAO extends DAO {

    // Récupération d'un objet User dont on donne l'identifiant (supposé fiable)
    public function getOne(int $id): User {
        $stmt = $this->pdo->prepare("SELECT * FROM personnel join type_personnel using (Id_Type_Personnel) WHERE Id_Personnel = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new User($row['Id_Personnel'], $row['Nom'], $row['Prenom'], $row['Sexe'], $row['Adresse'], $row['Ville'], $row['Dob'], $row['typeLibellé'], $row['Cp'], $row['Pseudo']);
    }

    // Verification si la personne entre les infrmation necessaire a son authentification
    public function check(string $username, string $mdp) {
        $username = ucfirst($username);
        $stmt = $this->pdo->prepare("SELECT * FROM personnel join type_personnel using (Id_Type_Personnel) WHERE pseudo = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row){ // Si il existe 
            $dob = date_format(date_create($row['Dob']), 'dmY');
            if(empty( $row['Mdp']) && $dob === $mdp){ // Si le mot de passe n'est pas initialisé dans la BDD et que le mdp correspond a a la DOB alors on entre en session le paramètre initPassWord
                $_SESSION['initPassWord'] = true;
                return new User($row['Id_Personnel'], $row['Nom'], $row['Prenom'], $row['Sexe'], $row['Adresse'], $row['Ville'], $row['Dob'], $row['typeLibellé'], $row['Cp'], $row['Pseudo']);
            }
            if(password_verify($mdp, $row['Mdp'])){ // Sinon on vérifie si le mdp entré correspond a celui crypté dans la BDD
                return new User($row['Id_Personnel'], $row['Nom'], $row['Prenom'], $row['Sexe'], $row['Adresse'], $row['Ville'], $row['Dob'], $row['typeLibellé'], $row['Cp'], $row['Pseudo']);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    // Génère le pseudo selon le nom et le prénom (exemple marwane chaboune -> chahbounem)
    public function findPseudo(string $nom, string $prenom): string{
        return $nom.$prenom[0];
    }

    // Permet d'enregistrer le mdp crypté
    public function initPassWord($user, string $mdp){
        $cost = ['cost' => 12];
        $password = password_hash($mdp, PASSWORD_BCRYPT, $cost);
        $stmt = $this->pdo->prepare("UPDATE personnel set Mdp=:mdp"
                                        . " WHERE Id_Personnel=:id");
        $res = $stmt->execute(['id' => $user->Id_Personnel, 'mdp' => $password]);

        return $res;
    }

    // Récupération de tous les objets dans une table
    public function getAll(): array {
        $res = array();
        $stmt = $this->pdo->query("SELECT * FROM personnel join type_personnel using (Id_Type_Personnel)");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
            $res[] = new User($row['Id_Personnel'], $row['Nom'], $row['Prenom'], $row['Sexe'], $row['Adresse'], $row['Ville'], $row['Dob'], $row['typeLibellé'], $row['Cp'], $row['Pseudo']);
        return $res;
    }

    // Sauvegarde de l'objet $obj :
    //     $obj->id == UNKNOWN_ID ==> INSERT
    //     $obj->id != UNKNOWN_ID ==> UPDATE
    public function save(object $obj): int {
        if ($obj->Id_Personnel == DAO::UNKNOWN_ID) {
            $stmt = $this->pdo->prepare("INSERT INTO `personnel` (Nom, Prenom, Sexe, Adresse, Ville, Cp, Dob, Id_Type_Personnel, Pseudo) VALUES (:Nom, :Prenom, :Sexe, :Adresse, :Ville, :Cp, :Dob, :Id_Type_Personnel, :Pseudo)");
            $res = $stmt->execute(array(
                ":Nom" => ucfirst($obj->Nom),
                ":Prenom" => ucfirst($obj->Prenom),
                ":Sexe" => $obj->Sexe,
                ":Adresse" => $obj->Adresse,
                ":Ville" => ucfirst($obj->Ville),
                ":Cp" => $obj->Cp,
                ":Dob" => $obj->Dob,
                ":Id_Type_Personnel" => $obj->Id_Type_Personnel,
                ":Pseudo" => strtolower($obj->Pseudo)
            ));
            $obj->Id_Personnel = $this->pdo->lastInsertId();
            if(isset($_FILES['image'])){
                upload_pic($obj->Id_Personnel, "Media/img/personnel/", "image");
            }
        } else {
            $stmt = $this->pdo->prepare("UPDATE `personnel` SET Nom = :Nom, Prenom = :Prenom, Sexe = :Sexe, Adresse = :Adresse, Ville = :Ville, Cp = :Cp, Dob = :Dob, Id_Type_Personnel = :Id_Type_Personnel, Pseudo = :Pseudo"
                                        . " WHERE Id_Personnel=:Id_Personnel");
            $res = $stmt->execute(array(
                ":Nom" => ucfirst($obj->Nom),
                ":Prenom" => ucfirst($obj->Prenom),
                ":Sexe" => $obj->Sexe,
                ":Adresse" => $obj->Adresse,
                ":Ville" => ucfirst($obj->Ville),
                ":Cp" => $obj->Cp,
                ":Dob" => $obj->Dob,
                ":Id_Type_Personnel" => $obj->Id_Type_Personnel,
                ":Pseudo" => strtolower($obj->Pseudo),
                ":Id_Personnel" => $obj->Id_Personnel
            ));    
            if(isset($_FILES['image'])){
                upload_pic($obj->Id_Personnel, "Media/img/personnel/", "image");
            }    
        }
        return $res;
    }

    // Effacement de l'objet $obj (DELETE)
    public function delete(object $obj): int {
        $stmt = $this->pdo->prepare("DELETE FROM personnel WHERE Id_Personnel = ?");
        return $stmt->execute([$obj->Id_Personnel]);
    }

}