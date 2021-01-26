<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Contrôleur prenant en charge les demandes liées aux ressources de type Visiteur
 * @author baraban
 *
 */
class CI_Visiteurs extends CI_Controller {
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

    
    public function updateVisiteur($id) {

        $nom = $this->input->input_stream("nom");
        $prenom = $this->input->input_stream("prenom");
        $login = $this->input->input_stream("login");
        $mdp = $this->input->input_stream("mdp");
        $adresse = $this->input->input_stream("adresse");
        $codePostal = $this->input->input_stream("codePostal");
        $ville = $this->input->input_stream("ville");
        $dateEmbauche = $this->input->input_stream("dateEmbauche");

        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('nom', 'nom du visiteur', 'alpha_dash|max_length[32]');
        $this->form_validation->set_rules('prenom', 'prenom du visiteur', 'alpha_dash|max_length[32]');
        $this->form_validation->set_rules('login', 'login du visiteur', 'max_length[15]');
        $this->form_validation->set_rules('mdp', 'mdp du visiteur', 'max_length[15]');
        $this->form_validation->set_rules('adresse', 'adresse du visiteur', 'alpha_dash|max_length[15]');
        $this->form_validation->set_rules('codePostal', 'code postal du visiteur', 'integer|max_length[15]');
        $this->form_validation->set_rules('ville', 'ville du visiteur', 'alpha_dash|max_length[15]');
        $this->form_validation->set_rules('dateEmbauche', 'date embauche du visiteur',);

        if ($this->form_validation->run()) {
            // conversion de l'id et de la capacité en valeurs entières
            $id = intval($id);
    
            // mise à jour dans le modèle
            $this->unVisiteur->modifierVisiteur($id, $nom, $prenom, $login, $mdp, $adresse, $cp, $ville, $dateEmbauche);
            $response = ["status" => "Visiteur d' " . $id . " a été modifié",
                         "data" => [ "link" => site_url("/visiteurs/" . $id) ]
                        ];
            $this->output
                    ->set_status_header(200)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    
            }

        //else {
            //$response = ["status" => "Les données à modifier sont invalides", "errors" => $this->form_validation->error_array()];
            //$this->output
                    //->set_status_header(400)
                    //->set_content_type('application/json', 'utf-8')
                    //->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        //}


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