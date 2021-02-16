<?php  if (!defined('BASEPATH'))   exit('No direct script access allowed');

class RapportVisite_Model extends My_Model {
    
    /**
    * Fournit les nom et prénom de tous les médecins
    * @return array
    */
    public function getList($idVisiteur) : array {
        $query = "select id, idMedecin, dateVisite, idMotifVisite from rapportvisite where idVisiteur = ?";
        $cmd = $this->monPdo->prepare($query);
        $cmd->bindValue(1, $idVisiteur);
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
    public function getById($idVisiteur, $idRapport) {
        $query = "select id, idMedecin, dateVisite, idMotifVisite from rapportvisite 
                    where idVisiteur = ? and id = ?";
        $cmd = $this->monPdo->prepare($query);
        $cmd->bindValue(1, $idVisiteur);
        $cmd->bindValue(2, $idRapport);
        $cmd->execute();
        $ligne = $cmd->fetch(PDO::FETCH_OBJ);
        $cmd->closeCursor();
        if ( $ligne === false ) {
            $ligne = null;
        }
        return $ligne;
    }
}
?>