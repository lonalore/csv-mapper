<?php

namespace CsvMapper;

/**
 * MappingManager.
 */
class MappingManager {

  /**
   * @var array
   */
  private $mappings;

  /**
   * Set a mapping.
   *
   * @param string $key
   *  Mapping's key.
   * @param array $value
   *  Mapping's value.
   */
  function set($key, $value) {
    if (isset($this->mappings[$key])) {
      return FALSE;
    }
    else {
      return $this->mappings[$key] = $value;
    }
  }

  /**
   * Retrieve a mapping.
   *
   * @param string $key
   *  Mapping's key.
   *
   * @return array|bool
   */
  function get($key) {
    if (isset($this->mappings[$key])) {
      return $this->mappings[$key];
    }
    else {
      return FALSE;
    }
  }

  /**
   * Retrieve all mapping.
   *
   * @return array
   */
  function getAll() {
    return $this->mappings;
  }

  /**
   * Remove a mapping.
   *
   * @param string $key
   *  Mapping's key.
   *
   * @return bool
   */
  function remove($key) {
    if (isset($this->mappings[$key])) {
      unset($this->mappings[$key]);
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

}
