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
    $handler = new \SplFileObject($this->getPath(), "r");
    return $handler;
  }

  /**
   * @return void
   */
  public function close() {
    $this->getHandler()->fclose();
    $this->handler = NULL;
  }

  /**
   * @return void
   */
  public function reset() {
    $this->seek(0);
  }

  /**
   * @return int
   */
  public function getColumnsCount() {
    $fileColumns = count($this->getHandler()
      ->fgetcsv($this->getSeparator(), $this->getEnclosure()));
    $this->reset();
    return $fileColumns;
  }

  /**
   * @return int
   */
  public function getRowsCount() {
    $c = 0;
    while (!$this->getHandler()->eof()) {
      $content = $this->getHandler()->fgets();
      if ($content) {
        $c++;
      }
    }
    $this->reset();
    return $c;
  }

  /**
   * @return array|false
   */
  public function getRowAsArray() {
    $rawRow = $this->getHandler()
      ->fgetcsv($this->getSeparator(), $this->getEnclosure());
    return $rawRow;
  }

  /**
   * @return array|false
   */
  public function getFirstRowAsArray() {
    $this->reset();
    $rawRow = $this->getHandler()
      ->fgetcsv($this->getSeparator(), $this->getEnclosure());
    return $rawRow;
  }

  /**
   * Skips the first row.
   *
   * @return void
   */
  public function skipFirstRow() {
    $this->seek(1);
  }

  /**
   * @param int $number
   *
   * @return void
   */
  public function setRowNumber(int $number) {
    $this->seek($number);
  }

  /**
   * @return bool
   */
  public function hasRow() {
    return !$this->getHandler()->eof();
  }

  /**
   * SEEK an Spl object.
   *
   * There is a bug in php for seeking files seems solved php_version > PHP8.0.1
   * See https://bugs.php.net/bug.php?id=46569
   * & https://3v4l.org/O89dJ
   *
   * $Spl->seek() Works ok in all versions with offset 0 (first row)
   * On PHP_VERSION < 8.0.1:
   *  - Offset 1: seek() cannot seek at row 1. It will be done manually, rewind
   * file and reading first row
   *  - Rest of Offsets: The cursor remains at next row of $Offset
   *
   * @param int $offset
   */
  function seek($offset) {
    if (version_compare(PHP_VERSION, '8.0.1', '>=') || $offset == 0) {
      $this->getHandler()->seek($offset);
    }
    elseif ($offset == 1) {
      // Ensure to go at first row before exit.
      $this->getHandler()->rewind();
      // Read line 0. Cursor remains now at line 1.
      $this->getHandler()->fgets();
    }
    else {
      $this->getHandler()->seek($offset - 1);
    }
  }

}
