<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Contrôleur prenant en charge les demandes liées aux ressources de type Medicament
 * @author baraban
 */
class CI_Medicaments extends CI_Controller {
    /**
     * Initialise le contrôleur CI_Medicaments
     * Le modèle est chargé dès la création du contrôleur
     * car toutes les méthodes en ont besoin
     */
    public function __construct() {
        parent::__construct();
            $this->load->model('Medicament_Model', 'mMedicament');    
    }

    /**
     * Traite la demande par défaut sur le contrôleur CI_Medicaments
     * Appelée si aucune méthode spécifiée dans l'URL
     */
    public function getAll() {
        $tab = $this->mMedicament->getList();
        $response = ["status" => "OK", "data" => $tab];
        $json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($json);
    }

    /**
     * Récupère un Medicament à partir de l'identifiant $code
     * Prépare et envoie la réponse http : code statut, contenu
     * @param string $code
     */
    public function getOne($code) {
        $unMedicament = $this->mMedicament->getById($code);
        $response = ["status" => "OK", "data" => $unMedicament];
        
        $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
    /**
     * Traite un appel mal formé où une valeur numérique pour le code est attendu
     */
    public function error404($code) {
        $response = ["status" => "OK", "data" => "Code de ressource invalide ou inexistante"];
        $this->output
                ->set_status_header(404)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        
    }   
}
?>