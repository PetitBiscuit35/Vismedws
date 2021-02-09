<?php  if (!defined('BASEPATH'))   exit('No direct script access allowed');

class Visiteur_Model extends My_Model {

    /**
    * Fournit les id, nom et prénom de tous les visiteurs
    * @return array
    */
    public function getList() {
        $query = "select id, nom, prenom from Visiteur";
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
    * Fournit le visiteur correspondant à l'id spécifié
    * @param string $id
    * @return stdClass ou null
    */
    public function getById($id) {
        $query = "select * from Visiteur where id = :id";
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

    public function update($id, $nom, $prenom, $adresse, $cp, $ville, $dateEmbauche){

        $query = "update visiteur set nom=:nom, prenom=:prenom, adresse=:adresse, cp=:cp, ville=:ville, dateEmbauche=:dateEmbauche where id like :id";

        $cmd = $this->monPdo->prepare($query);
        $cmd->bindValue(":id", $id);
        $cmd->bindValue(":nom", $nom);
        $cmd->bindValue(":prenom", $prenom);
        $cmd->bindValue(":adresse", $adresse);
        $cmd->bindValue(":cp", $cp);
        $cmd->bindValue(":ville", $ville);
        $cmd->bindValue(":dateEmbauche", $dateEmbauche);

        $cmd->execute();

        $valueRow = $cmd->rowCount();
        $cmd->closeCursor();

        return $valueRow;        
        
    }

        /**
    * Fournit le visiteur correspondant à l'id spécifié
    * @param string $id
    * @return stdClass ou null
    */
    public function getByCredentials($login, $mdp){
        $query = "select id, nom, prenom, login, adresse, cp, ville, dateEmbauche from visiteur where login = :login and mdp = :mdp";
        $cmd = $this->monPdo->prepare($query);
        $cmd->bindValue("login", $login);
        $cmd->bindValue("mdp", $mdp);
        $cmd->execute();
        $ligne = $cmd->fetch(PDO::FETCH_OBJ);
        $cmd->closeCursor();
    
        return $ligne;
    }

}
?>