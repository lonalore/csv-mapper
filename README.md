CSV Mapper
==========

CSV Mapper is a PHP library, which parses CSV files into PHP array.

### Example

```php
$file = new \CSVMapper\Source\ExcelFile();
// $file->setColumnsAllowed(4);
$file->setFolder('./');
$file->setName('test.xlsx');
$file->setPath('./test.xlsx');

// First row from the file (if needed).
$header = $file->getFirstRowAsArray();

$mapper = new \CSVMapper\MappingManager();
$error = new \CSVMapper\ErrorManager();
$parser = new \CSVMapper\Parser\Parser();
$reader = new \CSVMapper\Reader\Reader();

$mapper->set('Column label: ' . $header[0], ['key' => 0, 'fn' => FALSE]);
$mapper->set('Column label: ' . $header[1], ['key' => 1, 'fn' => FALSE]);
$mapper->set('Column label: ' . $header[2], ['key' => 2, 'fn' => function($value) {
  return md5($value);
}]);

$parser->setErrorManager($error);
$parser->setMappingManager($mapper);

$reader->setFile($file);
$reader->setParser($parser);
$reader->skipFirstRow();

while ($reader->hasNextRow()) {
  $row = $reader->getNextRow();
  if ($row) {
    var_dump($row);
//    Array
//    (
//        [Column label: A] => Foo
//        [Column label: B] => Bar
//        [Column label: C] => 01677e4c0ae5468b9b8b823487f14524
//    )
  }
}
```
