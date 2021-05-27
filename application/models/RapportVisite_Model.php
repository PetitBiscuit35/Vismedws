<?php  if (!defined('BASEPATH'))   exit('No direct script access allowed');

class RapportVisite_Model extends My_Model {
    /**
    * Fournit les nom et prénom de tous les médecins
    * @return array
    */
    public function getList($idVisiteur) : array {
        $query = "select id, idMedecin, dateVisite, idMotifVisite from rapportvisite where idVisiteur = ? and etatArchive is false";
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
                    where idVisiteur = ? and id = ? and etatArchive is false";
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

    /**
    * Fournit le numéro de dernier rapport de visite du visiteur
    * @param string $idVisiteur
    * @return stdClass ou null
    */
    public function getDernierRapport($idVisiteur) {
        $query = "select id from rapportvisite where idVisiteur = ? and etatArchive is false order by id desc";
        $cmd = $this->monPdo->prepare($query);
        $cmd->bindValue(1, $idVisiteur);
        $cmd->execute();
        $ligne = $cmd->fetch(PDO::FETCH_OBJ);
        $cmd->closeCursor();
        if ( $ligne === false ) {
            $id = 0;
        }
        else {
            $id = $ligne->id;
        }
        return $id;
    }

    /**
    * Ajout d'un nouveau rapport
    */
    public function addNewRapport($idVisiteur, $idMedecin, $dateVisite, $dateCreaRapport, $bilan, $coefConfiance, $idMotifVisite) {

        $query = "insert into rapportvisite (idVisiteur, id, idMedecin, dateVisite, dateCreaRapport, bilan, coefConfiance, idMotifVisite, etatArchive)
        values (:idVisiteur, :id, :idMedecin, :dateVisite, :dateCreaRapport, :bilan, :coefConfiance, :idMotifVisite, :etatArchive);";

        $cmd = $this->monPdo->prepare($query);

        $cmd->bindValue('idVisiteur', $idVisiteur);

        $id = $this->mRapportVisite->getDernierRapport($idVisiteur) + 1;

        $cmd->bindValue('id', $id, PDO::PARAM_INT);
        $cmd->bindValue('idMedecin', $idMedecin, PDO::PARAM_INT);
        $cmd->bindValue('dateVisite', $dateVisite, PDO::PARAM_STR);
        $cmd->bindValue('dateCreaRapport', $dateCreaRapport, PDO::PARAM_STR);
        $cmd->bindValue('bilan', $bilan, PDO::PARAM_STR);
        $cmd->bindValue('coefConfiance', $coefConfiance, PDO::PARAM_INT);
        $cmd->bindValue('idMotifVisite', $idMotifVisite, PDO::PARAM_INT);
        $cmd->bindValue('etatArchive', FALSE, PDO::PARAM_BOOL);

        $cmd->execute();
    }

    public function ArchiverRapportVisite($idVisiteur, $idRapport) {

        $query = "update rapportvisite set etatArchive=TRUE where idVisiteur=:idVisiteur and id=:idRapport;";

        $cmd = $this->monPdo->prepare($query);

        $cmd->bindValue('idVisiteur', $idVisiteur);
        $cmd->bindValue('idRapport', $idRapport, PDO::PARAM_INT);

        $cmd->execute();
        $valueRow = $cmd->rowCount();
        
        if ($valueRow == 1) {
            return true;
        }
        else {
            return false;
        }

    }

    public function getEtatRapport($idVisiteur, $idRapport) {
        $query = "select etatArchive from rapportvisite where idVisiteur = ? and id = ?;";
        $cmd = $this->monPdo->prepare($query);
        $cmd->bindValue(1, $idVisiteur);
        $cmd->bindValue(2, $idRapport);
        $cmd->execute();
        $ligne = $cmd->fetch(PDO::FETCH_OBJ);
        $cmd->closeCursor();
        if ( $ligne === false ) {
            return $ligne = null;
        }
        else {
            return $ligne->etatArchive;
        }
    }
}
?>