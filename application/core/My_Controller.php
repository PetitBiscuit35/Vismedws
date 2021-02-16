<?php if (!defined('BASEPATH'))   exit('No direct script access allowed');

abstract class My_Controller extends CI_Controller { 

  public function __construct() 
  {
    parent::__construct();
    
    try{
      $this->load->model('Visiteur_Model', 'mVisiteur');
    }catch(Exception $e){
      $response = ["status" => "Base de données inaccessible" , "data"=>null];
            $json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $this->output
            ->set_status_header(500)
            ->set_content_type('application/json','utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) 
            ->_display();
            die();
    }
    
    
   

    $chaine64 = substr($this->input->get_request_header("Authorization", TRUE), 6);

    if (empty($chaine64) === TRUE ) 
    {
      $login = "vide";
      $password = "vide";
    }
    else
    {
      $pluschaine64 = base64_decode($chaine64, false);

      $chaineadiviser = explode(":", $pluschaine64);

      $login = $chaineadiviser[0];

      $password = $chaineadiviser[1];
    }

    $tab = $this->mVisiteur->getByCredentials($login,$password);

      /// Si le login et le mot de passe se trouvent dans la base et correspondent alors:
      if ($tab != FALSE)
      {
        $response = ["status" => "OK", "data" => $tab];
        $json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $this->output
        ->set_status_header(200)
        ->set_content_type('application/json','utf-8')
        ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) 
        ->_display();
        die();
      }
      elseif(empty($password))
      {
        $response = ["status" => "NO", "data" => "Unauthorized"];
        $json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $this->output
        ->set_status_header(401)
        ->set_content_type('application/json','utf-8')
        ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) 
        ->_display();
        die();
      }
      
    }
    

}




