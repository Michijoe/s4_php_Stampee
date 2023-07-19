<?php

/**
 * Classe Routeur
 * analyse l'uri et exécute la méthode associée  
 *
 */
class Routeur
{

  function debug_to_console($data)
  {
    $output = $data;
    if (is_array($output))
      $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
  }

  private $routes = [
    // uri,             classe,     méthode
    // ------------------------------------
    ["admin",         "Admin",    "gererEntite"],
    ["",              "Frontend", "afficherAccueil"],
    ["catalogue",     "Frontend", "afficherCatalogue"],
    ["enchere",       "Frontend", "afficherEnchere"],
    ["connecter",     "Frontend", "connecter"],
    ["creerCompte",   "Frontend", "creerCompte"],
    ["deconnecter",   "Frontend", "deconnecter"],
    ["miser",         "Frontend", "miser"],
  ];

  protected $oRequetesSQL; // objet RequetesSQL utilisé par tous les contrôleurs

  const BASE_URI = (ENV == "DEV") ? '/S4/Stampee/' : '/stampee/';
  // const BASE_URI = '/S4/Stampee/'; // dossier racine du site par rapport au dossier racine d'Apache 
  // const BASE_URI = '/'; // pour le PHP Server de Visual Studio Code

  const ERROR_FORBIDDEN = "HTTP 403";
  const ERROR_NOT_FOUND = "HTTP 404";

  /**
   * Valider l'URI
   * et instancier la méthode du contrôleur correspondante
   *
   */
  public function router()
  {
    try {
      $this->debug_to_console("je suis dans router");

      // contrôle de l'uri si l'action coïncide

      $uri =  $_SERVER['REQUEST_URI'];
      if (strpos($uri, '?')) $uri = strstr($uri, '?', true);

      $this->debug_to_console("baseuri = ");
      $this->debug_to_console(self::BASE_URI);

      foreach ($this->routes as $route) {

        $routeUri     = self::BASE_URI . $route[0];
        $routeClasse  = $route[1];
        $routeMethode = $route[2];


        $this->debug_to_console("je suis dans l\'essai ");
        $this->debug_to_console($routeUri);

        if ($routeUri ===  $uri) {
          // on exécute la méthode associée à l'uri
          $this->debug_to_console($routeUri);
          $this->debug_to_console("j\'ai trouvé l\'essai ");
          $this->debug_to_console($routeUri);
          exit;
          $oRouteClasse = new $routeClasse;
          $oRouteClasse->$routeMethode();
        }
      }
      // aucune route ne correspond à l'uri
      // throw new Exception(self::ERROR_NOT_FOUND);
    } catch (Error | Exception $e) {
      // $this->erreur($e);
    }
  }

  /**
   * Méthode qui envoie un compte-rendu d'erreur
   * @param Exception $e
   */
  public function erreur($e)
  {
    $message = $e->getMessage();
    if ($message == self::ERROR_FORBIDDEN) {
      header('HTTP/1.1 403 Forbidden');
    } else if ($message == self::ERROR_NOT_FOUND) {
      header('HTTP/1.1 404 Not Found');
      (new Vue)->generer('vErreur404', [], 'gabarit-erreur');
    } else {
      header('HTTP/1.1 500 Internal Server Error');
      (new Vue)->generer("vErreur500", ['e' => $e], 'gabarit-erreur');
    }
    exit;
  }
}
