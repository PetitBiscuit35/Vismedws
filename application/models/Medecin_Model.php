<?php  if (!defined('BASEPATH'))   exit('No direct script access allowed');

class Medecin_Model extends My_Model {
   
    /**
    * Fournit les nom et prénom de tous les médecins
    * @return array
    */
    public function getList() {
        $query = "select id, nom, prenom from Medecin";
        $cmd = $this->monPdo->prepare($query);
        $cmd->execute();
        $lignes = $cmd->fetchAll(PDO::FETCH_OBJ);
        $cmd->closeCursor();
        if ( $lignes === false ) {
            $lignes = null;
        }
        return $lignes;
    }
    /**
    * Fournit le médecin correspondant à l'id spécifié
    * @param string $id
    * @return stdClass ou null
    */
    public function getById($id) {
        $query = "select * from Medecin where id = :id";
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
        $query = "UPDATE medecin 
        SET nom=:nom, 
        prenom=:prenom,
        adresse=:adresse, 
        codePostal=:codePostal,
        ville=:ville,
        tel=:tel,
        email=:email
        WHERE id=:id";
        $cmd = $this->monPdo->prepare($query);
        $cmd->bindParam(":id", $id);
        $cmd->bindParam(":nom", $nom);
        $cmd->bindParam(":prenom", $prenom);
        $cmd->bindParam(":adresse", $adresse);
        $cmd->bindParam(":codePostal", $codePostal);
        $cmd->bindParam(":ville", $villes);
        $cmd->bindParam(":email", $email);
        $cmd->bindParam(":tel", $tel);
        $cmd->bindParam(":specialiteComplementaire", $specialiteComplementaire);
        $cmd->bindParam(":coefNotoriete", $coefNotoriete);
        $cmd->bindParam(":estRemplacant", $estRemplacant);

        $cmd->execute();

        $valueRow = $cmd->rowCount();
        $cmd->closeCursor();

        return $valueRow;

        
        
    }

   

    
}
?>