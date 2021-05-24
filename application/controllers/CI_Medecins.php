<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Contrôleur prenant en charge les demandes liées aux ressources de type Medecin
 * @author baraban
 *
 */
class CI_Medecins extends My_Controller
{
    /**
     * Initialise le contrôleur CI_Medecins
     * Le modèle est chargé dès la création du contrôleur
     * car toutes les méthodes en ont besoin
     */
    public function __construct()
    {

        parent::__construct();
        try {
            $this->load->model('Medecin_Model', 'mMedecin');
        } catch (Exception $e) {
            $response = ["status" => "Base de données inaccessible", "data" => null];
            $json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                ->_display();
            die();
        }
    }

    /**
     * Traite la demande par défaut sur le contrôleur CI_Medecins
     * Appelée si aucune méthode spécifiée dans l'URL
     */
    public function getAll()
    {

        $tab = $this->mMedecin->getList();

        foreach ($tab as $unMedecin) {
            $unMedecin->link = site_url() . "/medecins/" . $unMedecin->id;
        }
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
    public function getOne($id)
    {
        $unMedecin = $this->mMedecin->getById($id);
        if ($unMedecin != null) {
            $response = ["status" => "OK", "data" => $unMedecin, "link" => site_url("/medecins/" . $id)];
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } else {
            $response = ["status" => "Id de médecin invalide ou inexistant"];
            $this->output
                ->set_status_header(404)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
    }

    public function getPostal($codePostal)
    {
        $unMedecin = $this->mMedecin->getCodePostal($codePostal);
        if ($unMedecin != null) {
            $response = ["status" => "OK", "data" => $unMedecin, "link" => site_url("/medecins/departement/" . $codePostal)];
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } else {
            $response = ["status" => "Erreur département médecin"];
            $this->output
                ->set_status_header(404)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
    }

    public function getAllDepartement()
    {

        $tab = $this->mMedecin->getListDepartement();

        if ($tab != null) {
            $response = ["status" => "OK", "data" => $tab, "link" => site_url("/medecins/departement")];

            $json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($json);
        } else {
            $response = ["status" => "Erreur département médecin"];
            $this->output
                ->set_status_header(404)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
    }

    /**
     * Récupère un médecin à partir de la spécialité
     * Prépare et envoie la réponse http : code statut, contenu
     * @param string $id
     */
    public function getSpecialite($specialiteComplementaire)
    {
        $unMedecin = $this->mMedecin->getMedecinSpecialite($specialiteComplementaire);
        if ($unMedecin != null) {
            $response = ["status" => "OK", "data" => $unMedecin, "link" => site_url("/medecins/specialiteComplementaire/" . $specialiteComplementaire)];
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } else {
            $response = ["status" => "Id de médecin invalide ou inexistant", "data" => $unMedecin, "link" => site_url("/medecins/specialiteComplementaire/" . $specialiteComplementaire)];
            $this->output
                ->set_status_header(404)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
    }

    public function delete($id)
    {

        // mise à jour dans le modèle
        $unMedecin = $this->mMedecin->getById($id);

        if ($unMedecin != null) {
            $this->mMedecin->delete($id);

            $response = [
                "status" => "Médecin d'id " . $id . " a été supprimé",
                "data" => ["link" => site_url("/medecins/" . $id)]
            ];
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } else {
            $response = ["status" => "Id de médecin invalide ou inexistant"];

            $this->output
                ->set_status_header(404)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
    }

    public function update($id)
    {

        // Récupération des données du corps (body) de la requête
        $nom = $this->input->input_stream("nom");
        $prenom = $this->input->input_stream("prenom");
        $adresse = $this->input->input_stream("adresse");
        $codePostal = $this->input->input_stream("codePostal");
        $ville = $this->input->input_stream("ville");
        $tel = $this->input->input_stream("tel");
        $email = $this->input->input_stream("email");

        $this->load->library('form_validation');
        $tab = $this->input->input_stream();
        $this->form_validation->set_data($tab);

        $this->form_validation->set_rules('nom', 'nom', 'max_length[50]');
        $this->form_validation->set_rules('prenom', 'prenom', 'max_length[50]');
        $this->form_validation->set_rules('adresse', 'adresse', 'max_length[100]');
        $this->form_validation->set_rules('codePostal', 'codePostal', 'integer|min_length[5]|max_length[5]');
        $this->form_validation->set_rules('ville', 'ville', 'max_length[50]');
        $this->form_validation->set_rules('tel', 'tel', 'integer|min_length[10]|max_length[10]');
        $this->form_validation->set_rules('email', 'email', 'max_length[50]');

        // conversion de l'id et de la capacité en valeurs entières
        if ($this->form_validation->run()) {
            $id = intval($id);

            // mise à jour dans le modèle
            $unMedecin = $this->mMedecin->getById($id);

            if ($unMedecin != null) {
                $result = $this->mMedecin->update($id, $nom, $prenom, $adresse, $codePostal, $ville, $tel, $email);

                $response = [
                    "status" => "Médecin d'id " . $id . " a été modifié",
                    "data" => ["link" => site_url("/medecins/" . $id)]
                ];
                $this->output
                    ->set_status_header(200)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            } else {
                $response = ["status" => "Id de médecin invalide ou inexistant"];

                $this->output
                    ->set_status_header(404)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }
        } else {
            $response = [
                "status" => "Les données du médecin à modifier sont invalides",
                "errors" => $this->form_validation->error_array()
            ];

            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
    }
    public function addMedecin()
    {
        // mise à jour dans le modèle
        $id = $this->mMedecin->getDernierMedecin() + 1;
        $nom = $this->input->input_stream("nom");
        $prenom = $this->input->input_stream("prenom");
        $adresse = $this->input->input_stream("adresse");
        $codePostal = $this->input->input_stream("codePostal");
        $ville = $this->input->input_stream("ville");
        $tel = $this->input->input_stream("tel");
        $email = $this->input->input_stream("email");
        $specialiteComplementaire = $this->input->input_stream("specialiteComplementaire");
        $coefNotoriete = $this->input->input_stream("coefNotoriete");
        $estRemplacant = $this->input->input_stream("estRemplacant");


        $this->load->library('form_validation');
        $tab = $this->input->input_stream();
        $this->form_validation->set_data($tab);

        $this->form_validation->set_rules('nom', 'nom', 'max_length[50]');
        $this->form_validation->set_rules('prenom', 'prenom', 'max_length[50]');
        $this->form_validation->set_rules('adresse', 'adresse', 'max_length[100]');
        $this->form_validation->set_rules('codePostal', 'codePostal', 'integer|min_length[5]|max_length[5]');
        $this->form_validation->set_rules('ville', 'ville', 'max_length[50]');
        $this->form_validation->set_rules('tel', 'tel', 'integer|min_length[10]|max_length[10]');
        $this->form_validation->set_rules('email', 'email', 'max_length[50]');

        if ($this->form_validation->run()) {
            $newMedecin = $this->mMedecin->addNewMedecin($id, $nom, $prenom, $adresse, $codePostal, $ville, $tel, $email,  $specialiteComplementaire, $coefNotoriete,  $estRemplacant);
            $codeStatut = 200;
            $response = ["status" => "OK", "data" => $id];
            $this->setResponse($codeStatut, $response);
        } else {
            $response = [
                "status" => "Les données du medecin sont invalides",
                "data" => $id,
                "errors" => $this->form_validation->error_array()
            ];

            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
    }

    /**
     * Traite un appel mal formé où une valeur numérique pour l'id est attendu
     */
    public function error404()
    {
        $response = ["status" => "OK", "data" => "Id de ressource invalide ou inexistante"];
        $this->output
            ->set_status_header(404)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    private function setResponse(int $codeStatut, array $response)
    {
        $json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->output
            ->set_status_header($codeStatut)
            ->set_content_type('application/json', 'utf-8')
            ->set_output($json);
    }
}
