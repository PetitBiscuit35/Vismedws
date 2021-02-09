<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Contrôleur prenant en charge les demandes liées aux ressources de type Visiteur
 * @author baraban
 *
 */
class CI_Visiteurs extends My_Controller {
    /**
     * Initialise le contrôleur CI_Visiteurs
     * Le modèle est chargé dès la création du contrôleur
     * car toutes les méthodes en ont besoin
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Visiteur_Model', 'mVisiteur');    
    }

    /**
     * Traite la demande par défaut sur le contrôleur CI_Visiteurs
     * Appelée si aucune méthode spécifiée dans l'URL
     */
    public function getAll() {
        $tab = $this->mVisiteur->getList();

        foreach ($tab as $unVisiteur)
        {
            $unVisiteur->link=site_url()."/visiteurs/".$unVisiteur->id;
        }

        $response = ["status" => "OK", "data" => $tab];
        $json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($json);
    }

    /**
     * Récupère un Visiteur à partir de l'identifiant $id
     * Prépare et envoie la réponse http : code statut, contenu
     * @param string $id
     */
    public function getOne($id) {
        $unVisiteur = $this->mVisiteur->getById($id);
        if ($unVisiteur != null) {
            $response = ["status" => "OK", "data" => $unVisiteur, "link" => site_url("/visiteurs/" . $id)];
        
            $this->output
                    ->set_status_header(200)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
        else{
            $response = ["status" => "Id de visiteur invalide ou inexistant"];
            $this->output
            ->set_status_header(404)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

    }

    
    public function update($id){
        
        // récupération des données du corps (body) de la requête
        $nom = $this->input->input_stream("nom");
        $prenom = $this->input->input_stream("prenom");
        $adresse = $this->input->input_stream("adresse");
        $cp = $this->input->input_stream("cp");
        $ville = $this->input->input_stream("ville");
        $dateEmbauche = $this->input->input_stream("dateEmbauche");

        $this->load->library('form_validation');
        $tab= $this->input->input_stream();
        $this->form_validation->set_data($tab);
        
        $this->form_validation->set_rules('nom','nom','required|max_length[30]');
        $this->form_validation->set_rules('cp','cp','required|integer');
        $this->form_validation->set_rules('prenom','prenom','required|max_length[256]');

        // conversion de l'id et de la capacité en valeurs entières
        if($this->form_validation->run()){
   
            // mise à jour dans le modèle
            $result = $this->mVisiteur->update($id, $nom, $prenom, $adresse, $cp, $ville, $dateEmbauche);
            $response = ["status" => "Visiteur modifié " . $id ,
                         "data" => [ "link" => site_url("/visiteurs/" . $id) ]
                        ];
            $this->output
                    ->set_status_header(200)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
        else{
            $response = ["status" => "Les données de l'utilisateur sont invalides",
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
    public function error404() {
        $response = ["data" => "Id de ressource invalide ou inexistante"];
        $this->output
                ->set_status_header(400)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        
    }
    
}
?>