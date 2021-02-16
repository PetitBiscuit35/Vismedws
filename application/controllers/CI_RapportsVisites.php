<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Contrôleur prenant en charge les demandes liées aux ressources de type RapportVisite
 * @author baraban
 *
 */
class CI_RapportsVisites extends My_Controller {
    /**
     * Initialise le contrôleur CI_RapportsVisites
     * Le modèle est chargé dès la création du contrôleur
     * car toutes les méthodes en ont besoin
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('RapportVisite_Model', 'mRapportVisite');    
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

    public function addRapport($idVisiteur) {

        $idMedecin = $this->input->input_stream("idMedecin");
        $dateVisite = $this->input->input_stream("dateVisite");
        $dateCreaRapport = $this->input->input_stream("dateCreaRapport");
        $bilan = $this->input->input_stream("bilan");
        $coefConfiance = $this->input->input_stream("coefConfiance");
        $idMotifVisite = $this->input->input_stream("idMotifVisite");

        $this->load->library('form_validation');
        $tab= $this->input->input_stream();
        $this->form_validation->set_data($tab);
        
        $this->form_validation->set_rules('idMedecin','idMedecin','integer[11]');
        $this->form_validation->set_rules('dateVisite','dateVisite','date');
        $this->form_validation->set_rules('dateCreaRapport','dateCreaRapport','date');
        $this->form_validation->set_rules('bilan','bilan','max_length[255]');
        $this->form_validation->set_rules('coefConfiance','coefConfiance','integer[4]');
        $this->form_validation->set_rules('idMotifVisite','idMotifVisite','integer[11]');
        
        if($this->form_validation->run()) 
        {
            $newRapport = $this->mRapportVisite->addNewRapport($idVisiteur, $idMedecin, $dateVisite, $dateCreaRapport, $bilan, $coefConfiance, $idMotifVisite);
            $codeStatut = 200;
            $response = ["status" => "OK", "data" => $idVisiteur];
            $this->setResponse($codeStatut, $response);
        }
        else 
        {
            $response = ["status" => "Les données du rapport sont invalides",
            "errors"=>$this->form_validation->error_array()];
                        
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
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
