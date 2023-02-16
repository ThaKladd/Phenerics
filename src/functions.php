<?php

function arrayOf(string $type): Thakladd\Phenerics\Collection {
    $class_name = 'ArrayOf' . $type;
    if (!class_exists($class_name)) {
        $type_ok = preg_match('/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/', $type);
        //preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\\[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)*$/', $type);
        if ($type_ok) {
            eval('class ' . $class_name . ' extends Thakladd\Phenerics\Collection {}');
        } else {
            throw new Thakladd\Phenerics\Exceptions\TypeNotValidException('Type "' . $type . '" is not a valid string.');
        }
    }
    return new $class_name($type);
}
