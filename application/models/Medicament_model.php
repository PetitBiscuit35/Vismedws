<?php if (!defined('BASEPATH'))   exit('No direct script access allowed');

class Medicament_Model extends My_Model
{
    /**
     * Fournit les depotLegal et nom commercial de tous les médicaments
     * @return stdClass
     */
    public function getList()
    {
        $query = "select depotLegal, nomCommercial from medicament";
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
     * Fournit le médicament correspondant à l'id spécifié
     * @param string $id
     * @return stdClass ou null
     */
    public function getById($id)
    {
        $query = "select * "
            . "from medicament "
            . "where depotLegal = :depotLegal";
        $cmd = $this->monPdo->prepare($query);
        $cmd->bindValue("depotLegal", $id);
        $cmd->execute();
        $ligne = $cmd->fetch(PDO::FETCH_OBJ);
        $cmd->closeCursor();
        if ($ligne === false) {
            $ligne = null;
        }
        return $ligne;
    }

    /**
     * Update le médicament correspondant à l'id spécifié
     * @param string $depotLegal
     * @return stdClass ou null
     */
    public function update($depotLegal, $nomCommercial, $composition, $effets, $contreIndic, $prixEchantillon)
    {

        $unMedicament = $this->mMedicament->getById($depotLegal);

        if ($nomCommercial == NULL) {
            $nomCommercial = $unMedicament->nomCommercial;
        }

        if ($composition == NULL) {
            $composition = $unMedicament->composition;
        }

        if ($effets == NULL) {
            $effets = $unMedicament->effets;
        }

        if ($contreIndic == NULL) {
            $contreIndic = $unMedicament->contreIndic;
        }

        if ($prixEchantillon == NULL) {
            $prixEchantillon = $unMedicament->prixEchantillon;
        }

        $query = "update medicament set nomCommercial=:nomCommercial, composition=:composition, effets=:effets, contreIndic=:contreIndic, prixEchantillon=:prixEchantillon where depotLegal like :depotLegal;";

        $cmd = $this->monPdo->prepare($query);

        $cmd->bindValue(":depotLegal", $depotLegal, PDO::PARAM_STR);
        $cmd->bindValue(":nomCommercial", $nomCommercial, PDO::PARAM_STR);
        $cmd->bindValue(":composition", $composition, PDO::PARAM_STR);
        $cmd->bindValue(":effets", $effets, PDO::PARAM_STR);
        $cmd->bindValue(":contreIndic", $contreIndic, PDO::PARAM_STR);
        $cmd->bindValue(":prixEchantillon", $prixEchantillon, PDO::PARAM_INT);

        $cmd->execute();
        $valueRow = $cmd->rowCount();
        $cmd->closeCursor();

        return $valueRow;
    }
}
