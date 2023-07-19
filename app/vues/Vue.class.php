<?php

class Vue
{

  function debug_to_console($data)
  {
    $output = $data;
    if (is_array($output))
      $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
  }

  /**
   * Générer et afficher la page html complète associée à la vue
   * -----------------------------------------------------------
   * @param string $vue 
   * @param array $donnees, variables à insérer dans la page
   * @param string $gabarit
   * @param boolean $courriel
   * @return string si $courriel true, void sinon 
   */
  public function generer($vue, $donnees = array(), $gabarit = "gabarit-frontend", $courriel = false)
  {

    $this->debug_to_console("je suis dans générer vue");

    require_once 'app/vues/vendor/autoload.php';
    $loader = new \Twig\Loader\FilesystemLoader('app/vues/templates');
    $twig = new \Twig\Environment($loader, [
      // 'cache' => 'app/vues/templates/cache',
    ]);
    $dossierVue =    stristr($gabarit, 'frontend') ? 'frontend'
      : (stristr($gabarit, 'admin')    ? 'admin'
        : (stristr($gabarit, 'courriel') ? 'courriels'
          : (stristr($gabarit, 'erreur')   ? 'erreurs'
            : '')));
    $this->debug_to_console("je suis apres dossierVue");


    $donnees['templateMain'] = "$dossierVue/$vue.twig";
    $html = $twig->render("gabarits/$gabarit.twig", $donnees);

    $this->debug_to_console($donnees);
    $this->debug_to_console($html);

    if ($courriel) return $html;
    echo $html;
  }
}
