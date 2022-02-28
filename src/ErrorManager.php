<?php

namespace CSVMapper\Configuration;

/**
 * ErrorManager.
 */
class ErrorManager {

  /**
   * @var array
   */
  private $errors;

  /**
   * Add an error to the list.
   *
   * @param object $error
   */
  function add($error) {
    $this->errors[] = $error;
  }

  /**
   * Retrieve all errors concatenated.
   *
   * @param string $separator
   *  The String which will separate the errors.
   *
   * @return string
   */
  function getAll($separator) {
    return implode($separator, $this->errors);
  }

}
