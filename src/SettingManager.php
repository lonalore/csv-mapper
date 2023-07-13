<?php

namespace CsvMapper;

/**
 * SettingManager.
 */
class SettingManager {

  /**
   * @var array
   */
  private $settings;

  /**
   *
   */
  function __construct() {
    $this->settings = [];
  }

  /**
   * Store a setting.
   *
   * @param string $key
   *  Setting's key.
   * @param object $value
   *  Setting's value.
   *
   * @return bool
   */
  function set($key, $value) {
    if (isset($this->settings[$key])) {
      return FALSE;
    }
    else {
      $this->settings[$key] = $value;
      return TRUE;
    }
  }

  /**
   * Retrieve a setting.
   *
   * @param string $key
   *  Setting's key.
   *
   * @return object|bool
   */
  function get($key) {
    if (isset($this->settings[$key])) {
      return $this->settings[$key];
    }
    else {
      return FALSE;
    }
  }

  /**
   * Remove a setting.
   *
   * @param string $key
   *  Setting's key.
   *
   * @return bool
   */
  function remove($key) {
    if (isset($this->settings[$key])) {
      unset($this->settings[$key]);
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

}
