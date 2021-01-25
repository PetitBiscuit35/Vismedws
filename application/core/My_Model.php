<?php  if (!defined('BASEPATH'))   exit('No direct script access allowed');

class My_Model extends CI_Model { 
protected  $monPdo; // @var PDO $monPDO
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

}