<?php  if (!defined('BASEPATH'))   exit('No direct script access allowed');

class Visiteur_Model extends My_Model {
    /**
    * Fournit les id, nom et prénom de tous les visiteurs
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
    * Fournit le visiteur correspondant à l'id spécifié
    * @param string $id
    * @return stdClass ou null
    */
    public function getById($id) {
        $query = "select id, nom, prenom, login from visiteur where id = :id";
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
    * Authentification
    *
    */
    public function getByCredentials($login, $password) {

        $query = "select login, mdp from visiteur where login = :login AND mdp = :mdp";
        $cmd = $this->monPdo->prepare($query);
        $cmd->bindValue("login", $login);
        $cmd->bindValue("mdp", $password);
        $cmd->execute();
        $ligne = $cmd->fetch(PDO::FETCH_OBJ);

        return $ligne;

        /* $cmd->closeCursor();

        if ( $ligne === $password ) {
            $auth = TRUE;
        }
        else 
        {
            $auth = FALSE;
        }
        return $auth; */
    }
}

?>