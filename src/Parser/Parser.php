<?php

namespace CSVMapper\Parser;

use CSVMapper\MappingManager;
use CSVMapper\ErrorManager;

/**
 * Parser.
 */
class Parser {

  /**
   * @var \CSVMapper\MappingManager
   */
  private $mappingManager;

  /**
   * @var \CSVMapper\ErrorManager
   */
  private $errorManager;

  /**
   * @return mixed
   */
  public function getMappingManager() {
    return $this->mappingManager;
  }

  /**
   * @param \CSVMapper\MappingManager $mappingManager
   *
   * @return void
   */
  public function setMappingManager(MappingManager $mappingManager) {
    $this->mappingManager = $mappingManager;
  }

  /**
   * @return mixed
   */
  public function getErrorManager() {
    return $this->errorManager;
  }

  /**
   * @param \CSVMapper\ErrorManager $errorManager
   *
   * @return void
   */
  public function setErrorManager(ErrorManager $errorManager) {
    $this->errorManager = $errorManager;
  }

  /**
   * @param array $row
   *  The extracted row to precces.
   *
   * @return array
   *  Row parsed.
   */
  public function parse($row) {
    $result = [];

    foreach ($this->mappingManager->getAll() as $key => $field) {
      if (isset($field['key']) && !is_null($field['key'])) {
        $input = $this->removeQuotes($row[$field['key']]);
      }
      else {
        $input = $field['value'];
      }

      if (isset($field['test']) && $field['test'] && !$field['test']($input)) {
        $this->errorManager->add("Field {$key} didn't pass the test!");
        return FALSE;
      }
      elseif (isset($field['fn']) && $field['fn']) {
        $result[$key] = is_array($field['fn']) ? call_user_func_array($field['fn'], [$input]): $field['fn']($input);
      }
      else {
        $result[$key] = $input;
      }
    }

    return $result;
  }

  /**
   * @param string $input
   *
   * @return string
   */
  public function removeQuotes($input) {
    if (strlen($input) > 0) {
      if (substr($input, 0, 1) == '"') {
        $input = substr($input, 1);
      }

      if (substr($input, -1) == '"') {
        $input = substr($input, 0, -1);
      }
    }

    return $input;
  }

}
