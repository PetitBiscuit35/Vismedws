<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Contrôleur prenant en charge les demandes liées aux ressources de type RapportVisite
 * @author baraban
 *
 */
class CI_RapportsVisites extends CI_Controller {
    /**
     * Initialise le contrôleur CI_RapportsVisites
     * Le modèle est chargé dès la création du contrôleur
     * car toutes les méthodes en ont besoin
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('RapportVisite_model', 'mRapportVisite');    
    }

    /**
     * Traite la demande par défaut sur le contrôleur CI_RapportsVisites
     * Appelée si aucune méthode spécifiée dans l'URL
     */
    public function getAll($idVisiteur) {
        $lesRapportsVisites = $this->mRapportVisite->getList($idVisiteur);
        $response = ["status" => "OK", "data" => $lesRapportsVisites];
        
        $this->setResponse(200, $response);
    }

    /**
     * Récupère un rapport de visite à partir de $idVisiteur et $idRap
     * Prépare et envoie la réponse http : code statut, contenu
     * @param int $id
     */
    public function getOne(string $idVisiteur, string $idRap) {
        $unRapport = $this->mRapportVisite->getById($idVisiteur, $idRap);
        $codeStatut = 200;
        $response = ["status" => "OK", "data" => $unRapport];
        $this->setResponse($codeStatut, $response);
    }

    /**
     * Traite un appel mal formé où une valeur numérique pour l'id est attendu
     */
    public function error404(string $id) {
        $response = ["status"  => "OK", "data" => "Id de ressource invalide ou inexistante"];
        $this->setResponse(404, $response);       
    }
    /**
     * Affecte code statut, type de contenu et contenu de la réponse http
     * @param int $codeStatut
     * @param array $response
     */
    private function setResponse(int $codeStatut, array $response) {
        $json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->output
                ->set_status_header($codeStatut)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($json);        
    }
}
?>
