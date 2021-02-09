<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Contrôleur prenant en charge les demandes liées aux ressources de type Medecin
 * @author baraban
 *
 */
class CI_Medecins extends My_Controller {
    /**
     * Initialise le contrôleur CI_Medecins
     * Le modèle est chargé dès la création du contrôleur
     * car toutes les méthodes en ont besoin
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Medecin_Model', 'mMedecin');    
    }

    /**
     * Traite la demande par défaut sur le contrôleur CI_Medecins
     * Appelée si aucune méthode spécifiée dans l'URL
     */
    public function getAll() {
        $tab = $this->mMedecin->getList();
        $response = ["status" => "OK", "data" => $tab];
        $json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($json);
    }
    /**
     * Récupère un médecin à partir de l'identifiant $id
     * Prépare et envoie la réponse http : code statut, contenu
     * @param string $id
     */
    public function getOne($id) {
        $unMedecin = $this->mMedecin->getById($id);
        $response = ["status" => "OK", "data" => $unMedecin];
        $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
    /**
     * Traite un appel mal formé où une valeur numérique pour l'id est attendu
     */
    public function error404($id) {
        $response = ["status" => "OK", "data" => "Id de ressource invalide ou inexistante"];
        $this->output
                ->set_status_header(404)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
?>