<?php

declare(strict_types=1);

namespace Thakladd\Phenerics;

use Thakladd\Phenerics\Exceptions\WrongTypeException;
use Thakladd\Phenerics\Exceptions\KeyNotFoundException;
use Thakladd\Phenerics\Exceptions\KeyExistsException;

class Collection implements \Iterator, \ArrayAccess, \Countable {

    public string $type = '';
    public int $current = 0;
    public array $list = [];

    function __construct(string $type) {
        $this->type = $type;
    }

    function __invoke(array $list) {
        foreach ($list as $key => $item) {
            $this->add($key, $item);
        }
        return $this;
    }

    private function getType(mixed $item): string {
        $type = gettype($item);
        if ($type === 'object' && $this->type !== 'object') {
            return get_class($item);
        }
        return $type;
    }

    function add(mixed $key = null, mixed $item = null): void {
        $item = $item ?? $key;
        $key = $item ? $key : null;

        $type_match = $this->getType($item) === $this->type;
        if (!$type_match) {
            throw new WrongTypeException('Wrong type in array. "' . $this->type . '" expected. "' . gettype($item) . '" was gotten.');
        }

        if ($key) {
            if (isset($this->list[$key])) {
                throw new KeyExistsException('Key "' . $key . '" exists. Use replace method to add on existing keys.');
            }
            $this->list[$key] = $item;
        } else {
            $this->list[] = $item;
        }
    }

    function replace(mixed $key = null, mixed $item = null): void {
        try {
            $this->add($key, $item);
        } catch (KeyExistsException $e) {
            $item = $item ?? $key;
            $key = $item ? $key : null;
            $this->list[$key] = $item;
        }
    }

    function remove(mixed $key): void {
        if (isset($this->list[$key])) {
            unset($this->list[$key]);
        } else {
            throw new KeyNotFoundException('Key "' . $key . '" not in collection.');
        }
    }

    function removeValue(mixed $value): void {
        if (($key = array_search($value, $this->list)) !== false) {
            unset($this->list[$key]);
        }
    }

    function get(mixed $key = null): mixed {
        if (isset($this->list[$key])) {
            return $this->list[$key];
        }
        throw new KeyNotFoundException('Key "' . $key . '" not in collection.');
    }

    public function all(): array {
        return $this->list;
    }

    public function current(): mixed {
        return $this->list[$this->current];
    }

    public function key(): mixed {
        return $this->current;
    }

    public function next(): void {
        ++$this->current;
    }

    public function rewind(): void {
        $this->current = 0;
    }

    public function valid(): bool {
        return isset($this->list[$this->current]);
    }

    public function offsetExists(mixed $offset): bool {
        return isset($this->list[$offset]);
    }

    public function offsetGet(mixed $offset): mixed {
        return $this->list[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void {
        $this->add($offset, $value);
    }

    public function offsetUnset(mixed $offset): void {
        unset($this->list[$offset]);
    }

    public function count(): int {
        return count($this->list);
    }
}
