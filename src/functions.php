<?php

function arrayOf(string $type): Thakladd\Phenerics\Collection {
    $class_name = 'ArrayOf' . $type;
    if (!class_exists($class_name)) {
        $type_ok = preg_match('/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/', $type);
        if ($type_ok) {
            //Simple
            eval('class ' . $class_name . ' extends Thakladd\Phenerics\Collection {}');
            /**
             * Alternative
             * $filename = __DIR__ . '/cache/' . $class_name . '.php';
             * if (!is_file($filename)) {
             *     $class = 'class ' . $class_name . ' extends Thakladd\Phenerics\Collection {}';
             *     file_put_contents($filename, '<?php' . PHP_EOL . $class);
             * }
             * require_once($filename);
             */
        } else {
            throw new Thakladd\Phenerics\Exceptions\TypeNotValidException('Type "' . $type . '" is not a valid string.');
        }
    }
    return new $class_name($type);
}
