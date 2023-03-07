<?php

function arrayOf(string $type, bool $cache = false): Thakladd\Phenerics\Collection {
    $class_name = 'ArrayOf' . $type;
    $class = '<?php class ' . $class_name . ' extends Thakladd\Phenerics\Collection {} ?>';
    if (!class_exists($class_name)) {
        if (!checkClassName($type)) {
            throw new Thakladd\Phenerics\Exceptions\TypeNotValidException('Type "' . $type . '" is not a valid string.');
        }
        if (!$cache) {
            eval($class);
        } else {
            storeClass($class_name, $class);
        }
    }
    return new $class_name($type);
}

function storeClass(string $class_name, string $class) {
    $filename = __DIR__ . '/cache/' . $class_name . '.php';
    if (!is_file($filename)) {
        file_put_contents($filename, $class);
    }
    require_once($filename);
    return true;
}

function checkClassName(string $class_name) {
    return preg_match('/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/', $class_name);
}
