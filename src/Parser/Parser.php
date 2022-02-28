<?php

namespace CSVMapper\Parser;

use CSVMapper\Configuration\MappingManager;
use CSVMapper\Configuration\ErrorManager;

/**
 * Parser.
 */
class Parser {

  /**
   * @var \CSVMapper\Configuration\MappingManager
   */
  private $mappingManager;

  /**
   * @var \CSVMapper\Configuration\ErrorManager
   */
  private $errorManager;

  /**
   * @return mixed
   */
  public function getMappingManager() {
    return $this->mappingManager;
  }

  /**
   * @param \CSVMapper\Configuration\MappingManager $mappingManager
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
   * @param \CSVMapper\Configuration\ErrorManager $errorManager
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
        $input = $this->remove_quotes($row[$field['key']]);
      }
      else {
        $input = $field['value'];
      }

      if (isset($field['test']) && $field['test'] && !$field['test']($input)) {
        $this->errorManager->add("Field {$key} didn't pass the test!");
        return FALSE;
      }
      else {
        if (isset($field['fn']) && $field['fn']) {
          $result[$key] = $field['fn']($input);
        }
        else {
          $result[$key] = $input;
        }
      }
    }

    return $result;
  }

  /**
   * @param string $input
   *
   * @return string
   */
  public function remove_quotes($input) {
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
