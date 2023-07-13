<?php

namespace CsvMapper\Reader;

use CsvMapper\Parser\Parser;
use CsvMapper\Source\SourceBase;
use CsvMapper\Exception\WrongColumnsNumberException;

/**
 * Reader.
 */
class Reader {

  /**
   * @var \CsvMapper\Source\SourceBase
   */
  private $source = NULL;

  /**
   * @var \CsvMapper\Parser\Parser
   */
  private $parser = NULL;

  /**
   * @return null
   */
  public function getFile() {
    return $this->source;
  }

  /**
   * @param \CsvMapper\Source\SourceBase $source
   *
   * @return void
   */
  public function setFile(SourceBase $source) {
    $this->source = $source;
    $this->isFileOk();
  }

  /**
   * @return null
   */
  public function getParser() {
    return $this->parser;
  }

  /**
   * @param \CsvMapper\Parser\Parser $parser
   *
   * @return void
   */
  public function setParser(Parser $parser) {
    $this->parser = $parser;
  }

  /**
   * Skips the first row.
   *
   * @return void
   */
  public function skipFirstRow() {
    $this->source->skipFirstRow();
  }

  /**
   * @return void
   * @throws \CsvMapper\Exception\WrongColumnsNumberException
   */
  private function isFileOk() {
    $this->source->checkProperty('folder');
    $this->source->checkProperty('name');
    $this->checkColumnsNumber();
  }

  /**
   * @return void
   * @throws \CsvMapper\Exception\WrongColumnsNumberException
   */
  private function checkColumnsNumber() {
    $sourceColumns = $this->source->getColumnsCount();
    $allowedColumns = $this->source->getColumnsAllowed();
    if ($allowedColumns && $sourceColumns != $allowedColumns) {
      $this->source->close();
      throw new WrongColumnsNumberException(sprintf("Expected %d columns, found %d!", $allowedColumns, $sourceColumns), 1);
    }
  }

  /**
   * @return null
   */
  public function getNextRow() {
    $rawRow = $this->source->getRowAsArray();
    if (!empty($rawRow)) {
      return $this->parser->parse($rawRow);
    }
    else {
      return NULL;
    }
  }

  /**
   * @return mixed
   */
  public function hasNextRow() {
    $hasRow = $this->source->hasRow();
    return $hasRow;
  }

  /**
   * @param int $number
   *
   * @return void
   */
  public function setRowNumber(int $number) {
      $this->source->setRowNumber($number);
  }

  /**
   * @return void
   */
  public function close() {
    $this->source->close();
  }

}
