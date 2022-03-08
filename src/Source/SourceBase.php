<?php

namespace CSVMapper\Source;

use CSVMapper\Exception\PropertyMissingException;
use CSVMapper\Exception\ConfigurationMissingException;

/**
 * SourceBase.
 */
abstract class SourceBase {

  /**
   * Getter and setter for folder.
   *
   * @var null
   */
  private $folder = NULL;

  /**
   * Getter and setter for name.
   *
   * @var null
   */
  private $name = NULL;

  /**
   * Getter and setter for path.
   *
   * @var null
   */
  private $path = NULL;

  /**
   * Getter and setter for columnsAllowed.
   *
   * @var null
   */
  private $columnsAllowed = NULL;

  /**
   * Getter and setter for handler.
   *
   * @var null
   */
  protected $handler = NULL;

  /**
   * @return null
   */
  public function getFolder() {
    return $this->folder;
  }

  /**
   * @param $folder
   *
   * @return void
   */
  public function setFolder($folder) {
    $this->folder = $folder;
  }

  /**
   * @return null
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @param $name
   *
   * @return void
   */
  public function setName($name) {
    $this->name = $name;
  }

  /**
   * @return string|null
   */
  public function getPath() {
    if ($this->path != NULL) {
      return $this->path;
    }
    elseif ($this->folder != NULL && $this->name != NULL) {
      return sprintf("%s/%s", $this->getFolder(), $this->getName());
    }
    else {
      return NULL;
    }
  }

  /**
   * @param $path
   *
   * @return void
   */
  public function setPath($path) {
    $this->path = $path;
  }

  /**
   * @return false|null
   */
  public function getColumnsAllowed() {
    if (empty($this->columnsAllowed)) {
      return FALSE;
    }
    else {
      return $this->columnsAllowed;
    }
  }

  /**
   * @param $columnsAllowed
   *
   * @return void
   */
  public function setColumnsAllowed($columnsAllowed) {
    $this->columnsAllowed = $columnsAllowed;
  }

  /**
   * @return mixed
   */
  public function getHandler() {
    if (empty($this->handler)) {
      $this->handler = $this->open();
    }
    return $this->handler;
  }

  /**
   * Check if properties and configurations are ok.
   *
   * @param $key
   *
   * @return void
   * @throws \CSVMapper\Exception\ConfigurationMissingException
   * @throws \CSVMapper\Exception\PropertyMissingException
   */
  public function checkProperty($key) {
    if (!property_exists($this, $key)) {
      throw new PropertyMissingException(sprintf("Property %s of Class File is missing!", $key), 2);
    }
    if (empty($this->{$key})) {
      throw new ConfigurationMissingException(sprintf("Configuration %s is missing!", $key), 2);
    }
  }

  /**
   * Check handler.
   *
   * @return bool
   */
  public function checkHandler() {
    if (empty($this->handler)) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }

  /**
   * open file.
   *
   * @return mixed
   */
  abstract public function open();

  /**
   * Close file.
   *
   * @return mixed
   */
  abstract public function close();

  /**
   * Reset file, move poiter to the beginning of the file.
   *
   * @return mixed
   */
  abstract public function reset();

  /**
   * Skips the first row.
   *
   * @return mixed
   */
  abstract public function skipFirstRow();

  /**
   * Return the number of columns contained in the file.
   *
   * @return mixed
   */
  abstract public function getColumnsCount();

  /**
   * Return the number of rows contained in the file.
   *
   * @return mixed
   */
  abstract public function getRowsCount();

  /**
   * Return the current row as an array.
   *
   * @return mixed
   */
  abstract public function getRowAsArray();

  /**
   * Return the first row as an array.
   *
   * @return mixed
   */
  abstract public function getFirstRowAsArray();

  /**
   * Check if the file has another row.
   *
   * @return mixed
   */
  abstract public function hasRow();

}
