<?php  if (!defined('BASEPATH'))   exit('No direct script access allowed');

class Medecin_Model extends My_Model {
   
    /**
    * Fournit les nom et prénom de tous les médecins
    * @return array
    */
    public function getList() {
        $query = "select id, nom, prenom from medecin";
        $cmd = $this->monPdo->prepare($query);
        $cmd->execute();
        $lignes = $cmd->fetchAll(PDO::FETCH_OBJ);
        $cmd->closeCursor();
        if ( $lignes === false ) {
            $lignes = null;
        }
        return $lignes;
    }

    public function getCodePostal($codePostal){
        $query = "select id, codePostal, nom, prenom from medecin where codePostal like :codePostal";
        $cmd = $this->monPdo->prepare($query);
        $cmd->bindValue("codePostal", $codePostal . '%');
        $cmd->execute();
        $ligne = $cmd->fetchAll(PDO::FETCH_OBJ);
        $cmd->closeCursor();
        if ( $ligne === false ) {
            $ligne = null;
        }
        return $ligne;
    }

    
    /**
    * Fournit le médecin correspondant à l'id spécifié
    * @param string $id
    * @return stdClass ou null
    */
    public function getById($id) {
        $query = "select * from medecin where id = :id";
        $cmd = $this->monPdo->prepare($query);
        $cmd->bindValue("id", $id);
        $cmd->execute();
        $ligne = $cmd->fetch(PDO::FETCH_OBJ);
        $cmd->closeCursor();
        if ( $ligne === false ) {
            $ligne = null;
        }
        return $ligne;
    }

    public function update($id, $name, $prenom, $adresse, $codePostal, $ville, $tel, $email){

        //modification de la table medecin
        $query = "UPDATE medecin 
        SET nom=:nom, 
        prenom=:prenom,
        adresse=:adresse, 
        codePostal=:codePostal,
        ville=:ville,
        tel=:tel,
        email=:email
        WHERE id=:id";

        //Préparation de la requête
        $cmd = $this->monPdo->prepare($query);

        //Lier un paramètre à un nom de variable spécifique
        $cmd->bindParam(":id", $id);
        $cmd->bindParam(":nom", $nom);
        $cmd->bindParam(":prenom", $prenom);
        $cmd->bindParam(":adresse", $adresse);
        $cmd->bindParam(":codePostal", $codePostal);
        $cmd->bindParam(":ville", $villes);
        $cmd->bindParam(":email", $email);
        $cmd->bindParam(":tel", $tel);

        //éxécution 
        $cmd->execute();

        $valueRow = $cmd->rowCount();
        $cmd->closeCursor();

        return $valueRow;    
    } 
}
?>