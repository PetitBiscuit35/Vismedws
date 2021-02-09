<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Contrôleur prenant en charge les demandes liées aux ressources de type Medicament
 * @author baraban
 */
class CI_Medicaments extends My_Controller {
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
    public function getAll()
    {
        $tab = $this->mMedicament->getList();

        foreach ($tab as $unMedicament)
        {
            $unMedicament->link=site_url()."/medicaments/".$unMedicament->depotLegal;
            
        }
        $response = ["status" => "OK", "data" => $tab];

        $json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($json); 
    }

    /**
     * Récupère un Medicament à partir de l'identifiant $depotLegal
     * Prépare et envoie la réponse http : code statut, contenu
     * @param string $depotLegal
     */
    public function getOne($depotLegal) {

        $unMedicament = $this->mMedicament->getById($depotLegal);

        if ($unMedicament != null)
        {
        $response = ["status" => "OK", "data" => $unMedicament, "link" => site_url("/medicaments/" . $depotLegal)];
        
        $this->output
         ->set_status_header(200)
         ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
        else {
        $response = ["status" => "ID de médicament invalide ou inexistant"];
        
        $this->output
        ->set_status_header(404)
         ->set_content_type('application/json', 'utf-8')
         ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
    }
    public function update($depotLegal){
        // récupération des données du corps (body) de la requête
        $nomCommercial = $this->input->input_stream("nomCommercial");
        $composition = $this->input->input_stream("composition");
        $effets = $this->input->input_stream("effets");
        $contreIndic = $this->input->input_stream("contreIndic");
        $prixEchantillon = $this->input->input_stream("prixEchantillon");

        $this->load->library('form_validation');
        $tab= $this->input->input_stream();
        $this->form_validation->set_data($tab);
        
        $this->form_validation->set_rules('nomCommercial','nomCommercial','max_length[25]');
        $this->form_validation->set_rules('composition','composition','max_length[255]');
        $this->form_validation->set_rules('effets','effets','max_length[255]');
        $this->form_validation->set_rules('contreIndic','contreIndic','max_length[255]');
        $this->form_validation->set_rules('prixEchantillon','prixEchantillon','integer');

        // conversion de l'id et de la capacité en valeurs entières
        if($this->form_validation->run()){
    
            // mise à jour dans le modèle
            $result = $this->mMedicament->update($depotLegal, $nomCommercial, $composition, $effets, $contreIndic, $prixEchantillon);
            $response = ["status" => "Médicament modifié " . $depotLegal ,
                         "data" => [ "link" => site_url("/medicaments/" . $depotLegal) ]
                        ];
            $this->output
                    ->set_status_header(200)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
        else{
            $response = ["status" => "Les données du médicament sont invalides",
            "errors"=>$this->form_validation->error_array()];
                        
            $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

    }
}
    /**
     * Traite un appel mal formé où une valeur numérique pour le code est attendu
     */
    public function error404() {
        $response = ["data" => "Code de ressource invalide ou inexistante"];
        $this->output
                ->set_status_header(400)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        
    }   
}
?>