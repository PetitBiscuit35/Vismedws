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
        try{
            $this->load->model('Medecin_Model', 'mMedecin');  
        }
        catch(Exception $e){
            $response = ["status" => "Base de données inaccessible" , "data"=>null];
            $json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $this->output
            ->set_status_header(500)
            ->set_content_type('application/json','utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) 
            ->_display();
            die();
        }
    }

    /**
     * Traite la demande par défaut sur le contrôleur CI_Medecins
     * Appelée si aucune méthode spécifiée dans l'URL
     */
    public function getAll() {

        $tab= $this->mMedecin->getList();

        foreach($tab as $unMedecin){
            $unMedecin->link=site_url()."/medecins/".$unMedecin->id;

          
        }
        $response = ["status" => "OK" , "data"=>$tab];
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
 /*   public function getOne($id) {
        $unMedecin = $this->mMedecin->getById($id);
        if($unMedecin != null){
            $response = ["status" => "OK", "data"=>$unMedecin,"link" => site_url("/medecins/". $id)];
        $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        }else{
            $response = ["status" => "erreur id medecin"];
            $this->output
                    ->set_status_header(404)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
        
    }*/

    public function getPostal($codePostal){
        $unMedecin = $this->mMedecin->getCodePostal($codePostal);
        if($unMedecin != null){
            $response = ["status" => "OK", "data"=>$unMedecin,"link" => site_url("/medecins/". $codePostal)];
        $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        }else{
            $response = ["status" => "erreur id medecin"];
            $this->output
                    ->set_status_header(404)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
    }

    public function update($id){
        
        // récupération des données du corps (body) de la requête
        $name = $this->input->input_stream("name");
        $prenom = $this->input->input_stream("prenom");
        $adresse = $this->input->input_stream("adresse");
        $codePostal = $this->input->input_stream("codePostal");
        $ville = $this->input->input_stream("ville");
        $tel = $this->input->input_stream("tel");
        $email = $this->input->input_stream("email");

        $this->load->library('form_validation');
        $tab= $this->input->input_stream();
        $this->form_validation->set_data($tab);
        
        $this->form_validation->set_rules('name','name','required|max_length[30]');
        $this->form_validation->set_rules('codePostal','codePostal','required|integer');
        $this->form_validation->set_rules('prenom','prenom','required|max_length[256]');

        // conversion de l'id et de la capacité en valeurs entières
        if($this->form_validation->run()){
            $id = intval($id);
    
            // mise à jour dans le modèle
            $result = $this->mMedecin->update($id, $name, $prenom, $adresse, $codePostal, $ville, $tel, $email);
            $response = ["status" => "medecin modifié " . $id ,
                         "data" => [ "link" => site_url("/medecins/" . $id) ]
                        ];
            $this->output
                    ->set_status_header(200)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
        else{
            $response = ["status" => "Les données du medecin sont invalides",
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
        $response = ["status" => "OK", "data" => "Id de ressource invalide ou inexistante"];
        $this->output
                ->set_status_header(404)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        
    }
}
?>