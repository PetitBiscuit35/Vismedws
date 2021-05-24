<?php if (!defined('BASEPATH'))   exit('No direct script access allowed');

class Medecin_Model extends My_Model
{

    /**
     * Fournit les nom et prénom de tous les médecins
     * @return array
     */
    public function getList()
    {
        $query = "select id, nom, prenom from medecin";
        $cmd = $this->monPdo->prepare($query);
        $cmd->execute();
        $lignes = $cmd->fetchAll(PDO::FETCH_OBJ);
        $cmd->closeCursor();
        if ($lignes === false) {
            $lignes = null;
        }
        return $lignes;
    }

    /**
     * Renvoit les médecins correspondants au code postal spécifié
     */
    public function getCodePostal($codePostal)
    {
        $query = "select id, codePostal, adresse, ville, tel, email, specialiteComplementaire, coefNotoriete, estRemplacant, nom, prenom from medecin where codePostal like :codePostal";
        $cmd = $this->monPdo->prepare($query);
        $cmd->bindValue("codePostal", $codePostal . '%');
        $cmd->execute();
        $ligne = $cmd->fetchAll(PDO::FETCH_OBJ);
        $cmd->closeCursor();
        if ($ligne === false) {
            $ligne = null;
        }
        return $ligne;
    }

    /**
     * Renvoit les 2 premiers chiffres de tous les codes postaux
     */
    public function getListDepartement()
    {
        $query = "select distinct substr(codePostal, 1, 2) AS noDepts from medecin ORDER BY codePostal ASC";
        $cmd = $this->monPdo->prepare($query);
        $cmd->execute();
        $ligne = $cmd->fetchAll(PDO::FETCH_OBJ);
        $cmd->closeCursor();
        if ($ligne === false) {
            $ligne = null;
        }
        return $ligne;
    }

    /**
     * Fournit le médecin correspondant à l'id spécifié
     * @param string $id
     * @return stdClass ou null
     */
    public function getById($id)
    {
        $query = "select * from medecin where id = :id";
        $cmd = $this->monPdo->prepare($query);
        $cmd->bindValue("id", $id);
        $cmd->execute();
        $ligne = $cmd->fetch(PDO::FETCH_OBJ);
        $cmd->closeCursor();
        if ($ligne === false) {
            $ligne = null;
        }
        return $ligne;
    }

    /**
     * Permet de mettre à jour les informations d'un médecin spécifié par son id
     */
    public function update($id, $nom, $prenom, $adresse, $codePostal, $ville, $tel, $email)
    {

        $unMedecin = $this->mMedecin->getById($id);

        if ($nom == NULL) {
            $nom = $unMedecin->nom;
        }

        if ($prenom == NULL) {
            $prenom = $unMedecin->prenom;
        }

        if ($adresse == NULL) {
            $adresse = $unMedecin->adresse;
        }

        if ($codePostal == NULL) {
            $codePostal = $unMedecin->codePostal;
        }

        if ($ville == NULL) {
            $ville = $unMedecin->ville;
        }

        if ($email == NULL) {
            $email = $unMedecin->email;
        }

        if ($tel == NULL) {
            $tel = $unMedecin->tel;
        }

        // Modification de la table Medecin
        $query = "UPDATE medecin 
        SET nom=:nom, prenom=:prenom, adresse=:adresse, codePostal=:codePostal, ville=:ville, tel=:tel, email=:email WHERE id=:id";

        // Préparation de la requête
        $cmd = $this->monPdo->prepare($query);

        // Lier un paramètre à un nom de variable spécifique
        $cmd->bindParam(":id", $id);
        $cmd->bindParam(":nom", $nom);
        $cmd->bindParam(":prenom", $prenom);
        $cmd->bindParam(":adresse", $adresse);
        $cmd->bindParam(":codePostal", $codePostal);
        $cmd->bindParam(":ville", $ville);
        $cmd->bindParam(":email", $email);
        $cmd->bindParam(":tel", $tel);
        $cmd->execute();
        $valueRow = $cmd->rowCount();
        $cmd->closeCursor();

        return $valueRow;
    }

    /**
     * Fournit le numéro de dernier Medecin 
     * @param string $id
     * @return stdClass ou null
     */
    public function getDernierMedecin()
    {
        $query = "select id from medecin order by id desc LIMIT 1";
        $cmd = $this->monPdo->prepare($query);
        $cmd->execute();
        $ligne = $cmd->fetch(PDO::FETCH_OBJ);
        $cmd->closeCursor();
        if ($ligne === false) {
            $id = 0;
        } else {
            $id = $ligne->id;
        }
        return $id;
    }

    /**
     * Ajout d'un nouveau médecin
     */
    public function addNewMedecin($id, $nom, $prenom, $adresse, $codePostal, $ville, $tel, $email, $specialiteComplementaire, $coefNotoriete, $estRemplacant)
    {


        $query = "insert into medecin (id, nom, prenom, adresse, codePostal, ville, tel, email, specialiteComplementaire, coefNotoriete, estRemplacant)
        values (:id, :nom, :prenom, :adresse, :codePostal, :ville, :tel, :email, :specialiteComplementaire, :coefNotoriete, :estRemplacant);";

        $cmd = $this->monPdo->prepare($query);

        $cmd->bindValue('id', $id);
        $cmd->bindValue('nom', $nom);
        $cmd->bindValue('prenom', $prenom);
        $cmd->bindValue('adresse', $adresse);
        $cmd->bindValue('codePostal', $codePostal);
        $cmd->bindValue('ville', $ville);
        $cmd->bindValue('tel', $tel);
        $cmd->bindValue('email', $email);
        $cmd->bindValue('specialiteComplementaire', $specialiteComplementaire);
        $cmd->bindValue('coefNotoriete', $coefNotoriete);
        $cmd->bindValue('estRemplacant', $estRemplacant);


        $cmd->execute();
    }

    /**
     * Suppression d'un médecin
     */
    public function delete($id)
    {
        $query = "DELETE FROM medecin WHERE id=:id";
        $cmd = $this->monPdo->prepare($query);
        $cmd->bindValue("id", $id);
        $cmd->execute();
        $ligne = $cmd->fetch(PDO::FETCH_OBJ);
        $cmd->closeCursor();
        if ($ligne === false) {
            $ligne = null;
        }
        return $ligne;
    }

    /**
     * Fournit la specialité du médecin
     * @param string $id
     * @return stdClass ou null
     */
    public function getMedecinSpecialite($specialiteComplementaire)
    {
        $query = "SELECT id, codePostal, adresse, ville, tel, email, specialiteComplementaire, coefNotoriete, estRemplacant, nom, prenom from medecin WHERE specialiteComplementaire =:specialiteComplementaire";
        $cmd = $this->monPdo->prepare($query);
        $cmd->bindValue("specialiteComplementaire", $specialiteComplementaire);
        $cmd->execute();
        $ligne = $cmd->fetchAll(PDO::FETCH_OBJ);
        $cmd->closeCursor();
        if ($ligne === false) {
            $ligne = null;
        }
        return $ligne;
    }
}
