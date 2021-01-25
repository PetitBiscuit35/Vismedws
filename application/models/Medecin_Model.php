<?php  if (!defined('BASEPATH'))   exit('No direct script access allowed');

class Medecin_Model extends CI_Model {
    private  $monPdo; // @var PDO $monPDO
    /**
     * Initialise une instance de la classe Medecin_Model
     * - Récupère les paramètres de configuration liés au serveur MySql
     * - Prépare les requêtes SQL qui comportent des parties variables
     */
    public function __construct() {
         parent::__construct();
        // demande à charger les paramètres de configuration du fichier models.php
        $this->config->load("models");
        $server = $this->config->item("hostname");
        $bdd = $this->config->item("database");
        $user = $this->config->item("username");
        $mdp = $this->config->item("password");
        $driver = $this->config->item("dbdriver");

        // ouverture d'une connexion vers le serveur MySql dont la configuration vient d'être chargée
        try {
                $this->monPdo = new PDO($driver . ":host=" . $server . ";dbname=" . $bdd, 
                                                        $user, $mdp, 
                                                        array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'UTF8'",
                                                              PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));	
        }
        catch (Exception $e) {
                log_message('error', $e->getMessage());
                throw new Exception("Base de données inaccessible");
        }
    }
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
        $query = "select id, nom, prenom, email from Medecin where id = :id";
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
}
?>