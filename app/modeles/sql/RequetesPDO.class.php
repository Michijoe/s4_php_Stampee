<?php

/**
 * Classe des requêtes PDO 
 *
 */
class RequetesPDO
{

  protected $sql;

  const UNE_SEULE_LIGNE = true;

  function debug_to_console($data)
  {
    $output = $data;
    if (is_array($output))
      $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
  }

  /**
   * Requête $sql SELECT de récupération d'une ou plusieurs lignes
   * @param array   $params paramètres de la requête préparée
   * @param boolean $uneSeuleLigne true si une seule ligne à récupérer false sinon 
   * @return array|false false si aucune ligne retournée par fetch
   */
  public function getLignes($params = [], $uneSeuleLigne = false)
  {
    $this->debug_to_console("je suis dans getligne");

    $sPDO = SingletonPDO::getInstance();
    $this->debug_to_console("je suis a la ligne 34");

    $oPDOStatement = $sPDO->prepare($this->sql);
    $this->debug_to_console("je suis a la ligne 37");
    $nomsParams = array_keys($params);
    $this->debug_to_console("je suis a la ligne 39");
    foreach ($nomsParams as $nomParam) $oPDOStatement->bindParam(':' . $nomParam, $params[$nomParam]);
    $this->debug_to_console("je suis a la ligne 41");
    $oPDOStatement->execute();
    $this->debug_to_console("je suis a la ligne 43");
    $result = $uneSeuleLigne ? $oPDOStatement->fetch(PDO::FETCH_ASSOC) : $oPDOStatement->fetchAll(PDO::FETCH_ASSOC);
    $this->debug_to_console("je suis a la fin de getligne");

    return $result;
  }

  /**
   * Requête $sql de Création Update ou Delete d'une ligne
   * @param array   $params paramètres de la requête préparée
   * @return boolean|string chaîne contenant lastInsertId s'il est > 0
   */
  public function CUDLigne($params = [])
  {
    $sPDO = SingletonPDO::getInstance();

    $oPDOStatement = $sPDO->prepare($this->sql);
    foreach ($params as $nomParam => $valParam) $oPDOStatement->bindValue(':' . $nomParam, $valParam);
    $oPDOStatement->execute();
    if ($oPDOStatement->rowCount() <= 0) return false;
    if ($sPDO->lastInsertId() > 0)       return $sPDO->lastInsertId();
    return true;
  }

  /**
   * Démarrer une transaction
   * @return boolean
   */
  public function debuterTransaction()
  {
    $sPDO = SingletonPDO::getInstance();
    return $sPDO->beginTransaction();
  }

  /**
   * Valider une transaction
   * @return boolean
   */
  public function validerTransaction()
  {
    $sPDO = SingletonPDO::getInstance();
    return $sPDO->commit();
  }

  /**
   * Annuler une transaction
   * @return boolean
   */
  public function annulerTransaction()
  {
    $sPDO = SingletonPDO::getInstance();
    return $sPDO->rollBack();
  }
}
