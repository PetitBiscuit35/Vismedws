<?php  if (!defined('BASEPATH'))   exit('No direct script access allowed');

class Medicament_Model extends My_Model {
    
    /**
    * Fournit les depotLegal et nom commercial de tous les médicaments
    * @return stdClass
    */
    public function getList() {
        $query = "select depotLegal, nomCommercial from medicament";
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
    * Fournit le médicament correspondant à l'id spécifié
    * @param string $id
    * @return stdClass ou null
    */
    public function getById($id) {
        $query = "select depotLegal, nomCommercial, codeFamille, composition "
                . "from medicament "
                . "where depotLegal = :depotLegal";
        $cmd = $this->monPdo->prepare($query);
        $cmd->bindValue("depotLegal", $id);
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