<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

// ROUTES MEDECINS
$route['medecins']['get'] = 'CI_Medecins/getAll';
$route['medecins/(:num)']['get'] = 'CI_Medecins/getOne/$1';

// WORKING - Trouver les médecins en fonction d'un code postal donné
$route['medecins/departement/(:num)']['get'] = 'CI_Medecins/getPostal/$1';

// WORKING - Trouver les médecins en fonction de la specialité
$route['medecins/specialiteComplementaire/(:any)']['get'] = 'CI_Medecins/getSpecialite/$1';

// WORKING - (Pour l'application Android)
$route['medecins/departement']['get'] = 'CI_Medecins/getAllDepartement';

// WORKING - Mettre à jour un médecin
$route['medecins/(:num)']['put'] = 'CI_Medecins/update/$1';

$route['medecins/(:any)']['get'] = 'CI_Medecins/error404/$1';

// WORKING - Supprimer un médecin
$route['medecins/(:num)']['delete'] = 'CI_Medecins/delete/$1';

// WORKING - Ajout d'un médecin
$route['medecins']['post'] = 'CI_Medecins/addMedecin';

// ROUTES VISITEURS
$route['visiteurs']['get'] = 'CI_Visiteurs/getAll';
$route['visiteurs/(:any)']['get'] = 'CI_Visiteurs/getOne/$1';

// WORKING - Mettre à jour un visiteur donné
$route['visiteurs/(:any)']['put'] = 'CI_Visiteurs/update/$1';

// ROUTES RAPPORT VISITES
$route['visiteurs/(:any)/rapports']['get'] = 'CI_RapportsVisites/getAll/$1';
$route['visiteurs/(:any)/rapports/(:num)']['get'] = 'CI_RapportsVisites/getOne/$1/$2';
$route['visiteurs/(:any)/rapports/(:any)']['get'] = 'CI_RapportsVisites/error404/$1/$2';

// WORKING - Ajout d'un rapport de visite à un visiteur donné
$route['visiteurs/(:any)/rapports']['post'] = 'CI_RapportsVisites/addRapport/$1';

// ROUTES MEDICAMENTS
$route['medicaments']['get'] = 'CI_Medicaments/getAll';
$route['medicaments/(:any)']['get'] = 'CI_Medicaments/getOne/$1';

// WORKING - Mettre à jour d'un médicament donné
$route['medicaments/(:any)']['put'] = 'CI_Medicaments/update/$1';

// AUTRES
$route['default_controller'] = 'welcome';
$route['404_override'] = 'CI_Visiteurs/error404';
$route['translate_uri_dashes'] = FALSE;
