<?php

namespace CSVMapper\Source;

/**
 * CsvFile.
 */
class CsvFile extends SourceBase {

  /**
   * Field separator.
   *
   * @var string
   */
  private $separator = ';';

  /**
   * Field enclosure character.
   *
   * @var string
   */
  private $enclosure = '"';

  /**
   * @return string
   */
  public function getSeparator() {
    return $this->separator;
  }

  /**
   * @param $separator
   *
   * @return void
   */
  public function setSeparator($separator) {
    $this->separator = $separator;
  }

  /**
   * @return string
   */
  public function getEnclosure() {
    return $this->enclosure;
  }

  /**
   * @param $enclosure
   *
   * @return void
   */
  public function setEnclosure($enclosure) {
    $this->enclosure = $enclosure;
  }

  /**
   * @return false|resource
   */
  public function open() {
    $handler = fopen($this->getPath(), "r");
    return $handler;
  }

  /**
   * @return void
   */
  public function close() {
    if (!empty($this->handler)) {
      fclose($this->handler);
    }
    $this->handler = NULL;
  }

  /**
   * @return void
   */
  public function reset() {
    if (!empty($this->handler)) {
      fseek($this->handler, 0);
    }
  }

  /**
   * @return int
   */
  public function getColumnsCount() {
    $fileColumns = count(fgetcsv($this->getHandler(), 0, $this->getSeparator(), $this->getEnclosure()));
    $this->reset();
    return $fileColumns;
  }

  /**
   * @return int
   */
  public function getRowsCount() {
    $c = 0;
    if ($this->handler) {
      while (!feof($this->handler)) {
        $content = fgets($this->handler);
        if ($content) {
          $c++;
        }
      }
    }
    $this->reset();
    return $c;
  }

  /**
   * @return array|false
   */
  public function getRowAsArray() {
    $rawRow = fgetcsv($this->getHandler(), 0, $this->getSeparator(), $this->getEnclosure());
    return $rawRow;
  }

  /**
   * @return array|false
   */
  public function getFirstRowAsArray() {
    $this->reset();
    $rawRow = fgetcsv($this->getHandler(), 0, $this->getSeparator(), $this->getEnclosure());
    return $rawRow;
  }

  /**
   * Skips the first row.
   *
   * @return void
   */
  public function skipFirstRow() {
    if (!empty($this->handler)) {
      fseek($this->handler, 1);
    }
  }

  /**
   * @return bool
   */
  public function hasRow() {
    return !feof($this->getHandler());
  }

}
