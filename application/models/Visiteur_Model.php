<?php  if (!defined('BASEPATH'))   exit('No direct script access allowed');

class Visiteur_Model extends My_Model {
    /**
    * Fournit les id, nom et prénom de tous les Visiteurs
    * @return array
    */
    public function getList() {
        $query = "select id, nom, prenom from visiteur";
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
    * Fournit le Visiteur correspondant à l'id spécifié
    * @param string $id
    * @return stdClass ou null
    */
    public function getById($id) {
        $query = "select * from visiteur where id = :id";
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

    /**
     * Permet de mettre à jour les informations d'un Visiteur
     */
    public function update($id, $nom, $prenom, $adresse, $cp, $ville){

        // Permet de laisser les champs comme avant si on ne modifie par toutes les informations
        $unVisiteur = $this->mVisiteur->getById($id);
        
            if($nom == NULL) 
            {
               $nom = $unVisiteur->nom;
            }
    
            if($prenom == NULL)
            {
                $prenom = $unVisiteur->prenom;
            }
    
            if($adresse == NULL) 
            {
                $adresse = $unVisiteur->adresse;
            }
    
            if($cp == NULL) 
            {
                $cp = $unVisiteur->cp;
            }
    
            if($ville == NULL) {
                $ville = $unVisiteur->ville;
            }

        $query = "update visiteur set nom=:nom, prenom=:prenom, adresse=:adresse, cp=:cp, ville=:ville where id like :id";

        $cmd = $this->monPdo->prepare($query);

        $cmd->bindValue(":id", $id);
        $cmd->bindValue(":nom", $nom);
        $cmd->bindValue(":prenom", $prenom);
        $cmd->bindValue(":adresse", $adresse);
        $cmd->bindValue(":cp", $cp);
        $cmd->bindValue(":ville", $ville);
        $cmd->execute();
        $valueRow = $cmd->rowCount();
        $cmd->closeCursor();

        return $valueRow;        
    }

    /**
    * Vérifie si l'utilisateur et le mot de passe correspondent à ceux dans la base de données
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