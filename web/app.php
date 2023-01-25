<?php

session_start();

if(  $utilisateur_espace_client[0] == ''){

    $utilisateur_espace_client[0] = array(
		"id_user" 			=> $_SESSION['utilisateur_web_id'],
		"code_firme" 		=> $_SESSION['utilisateur_web_cfir'],
		"email"				=> $_SESSION['utilisateur_web_email'],
		"type" 				=> $_SESSION['utilisateur_web_type'],
		"raison_sociale" 	=> $_SESSION['raison_sociale']	,
		"nom" 				=> $_SESSION['utilisateur_web_nom'],
		"prenom" 			=> $_SESSION['utilisateur_web_prenom'],
		"profile" 			=> $_SESSION['utilisateur_profile'],
	);
   
   $_SESSION['utilisateur'][0]			= $utilisateur_espace_client[0];
   $_SESSION['data_user'][0]			= $utilisateur_espace_client[0];   

}







use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

// Use APC for autoloading to improve performance.
// Change 'sf2' to a unique prefix in order to prevent cache key conflicts
// with other applications also using APC.
/*
$apcLoader = new ApcClassLoader('sf2', $loader);
$loader->unregister();
$apcLoader->register(true);
*/

require_once __DIR__.'/../app/AppKernel.php';
//require_once __DIR__.'/../app/AppCache.php';

$kernel = new AppKernel('prod', true);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);

$response->send();
$kernel->terminate($request, $response);


/*if(!empty($_SESSION)){
	header("Location: https://www.telecontact.ma/espace-client/web/connection");	
}*/