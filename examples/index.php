<?php
include_once('../vendor/autoload.php');

class Test {
    public function __construct(public string $x = '') {
    }
}

function printIntList(ArrayOfInteger $list) {
    print_r($list->all());
}


$array = arrayOf('integer')([1, 2, 3, 4, 5]);
printIntList($array);
echo '<br>';
print_r($array[0]);
echo '<br>';
$array[] = 6;
print_r($array[5]);
echo '<br>';
print_r($array->get(2));
echo '<br>';
foreach ($array as $key => $value) {
    echo $key . ': ' . $value . ', ';
}

function printTestList(ArrayOfTest $list) {
    print_r($list->all());
}
echo '<br>';
$arrayOfTest = arrayOf('Test', true)([new Test('Hello'), new Test('On'), new Test('You')]);
$arrayOfTest->add(3, new Test('You'));
$arrayOfTest[] = new Test('Old');
$arrayOfTest['assoc'] = new Test('Glue');
printTestList($arrayOfTest);
