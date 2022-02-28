<?php

namespace CSVMapper\Source;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * ExcelFile.
 */
class ExcelFile extends SourceBase {

  /**
   * @var int
   */
  private $rowNumber = 1;

  /**
   * @return mixed|\PhpOffice\PhpSpreadsheet\Spreadsheet
   */
  public function open() {
    $inputFileName = $this->getPath();
    $spreadsheet = IOFactory::load($inputFileName);
    $this->rowNumber = 1;
    return $spreadsheet;
  }

  /**
   * @return float|int|mixed
   * @throws \PhpOffice\PhpSpreadsheet\Exception
   */
  public function getColumnsCount() {
    /** @var \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet */
    $spreadsheet = $this->getHandler();
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet */
    $worksheet = $spreadsheet->setActiveSheetIndex(0);

    $highestColumn = $worksheet->getHighestColumn();
    $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

    return $highestColumnIndex;
  }

  /**
   * @return array
   * @throws \PhpOffice\PhpSpreadsheet\Exception
   */
  public function getRowAsArray() {
    /** @var \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet */
    $spreadsheet = $this->getHandler();
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet */
    $worksheet = $spreadsheet->setActiveSheetIndex(0);

    $highestColumnIndex = $this->getColumnsCount();

    $values = [];

    for ($col = 1; $col <= $highestColumnIndex; $col++) {
      $cell = $worksheet->getCellByColumnAndRow($col, $this->rowNumber);
      $cellValue = $cell->getValue();
      array_push($values, $cellValue);
    }

    $this->rowNumber++;

    return $values;
  }

  public function getFirstRowAsArray() {
    /** @var \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet */
    $spreadsheet = $this->getHandler();
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet */
    $worksheet = $spreadsheet->setActiveSheetIndex(0);

    $highestColumnIndex = $this->getColumnsCount();

    $values = [];

    for ($col = 1; $col <= $highestColumnIndex; $col++) {
      $cell = $worksheet->getCellByColumnAndRow($col, 1);
      $cellValue = $cell->getValue();
      array_push($values, $cellValue);
    }

    return $values;
  }

  /**
   * Skips the first row.
   *
   * @return void
   */
  public function skipFirstRow() {
    $this->rowNumber = 2;
  }

  /**
   * @return bool
   * @throws \PhpOffice\PhpSpreadsheet\Exception
   */
  public function hasRow() {
    /** @var \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet */
    $spreadsheet = $this->getHandler();
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet */
    $worksheet = $spreadsheet->setActiveSheetIndex(0);

    $highestRow = $worksheet->getHighestRow();

    if ($highestRow == 1 && $worksheet->getCellByColumnAndRow(0, 1)->getValue() == NULL) {
      return FALSE;
    }
    elseif ($highestRow >= $this->rowNumber) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * @return mixed|void
   */
  public function reset() {
    $this->rowNumber = 1;
  }

  /**
   * @return mixed|void
   */
  public function close() {
    /** @var \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet */
    $spreadsheet = $this->getHandler();
    if (!empty($spreadsheet)) {
      $spreadsheet->disconnectWorksheets();
      unset($spreadsheet);
    }
    $this->handler = NULL;
  }

}
