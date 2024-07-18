<?php

declare(strict_types=1);

namespace JDecool\DataStructure;

/**
 * @template T
 * @phpstan-type EmptyOptional Optional<null>
 */
class Optional
{
    /**
     * @return EmptyOptional
     */
    public static function empty(): self
    {
        return new self(null);
    }

    /**
     * @param T $value
     * @return self<T>
     */
    public static function of(mixed $value): self
    {
        if ($value === null) {
            throw new \InvalidArgumentException('Value cannot be null');
        }

        return new self($value);
    }

    /**
     * @template V
     * @param ?V $value
     * @return self<V>|Empty
     */
    public static function ofNullable(mixed $value): self // @phpstan-ignore-line
    {
        if ($value === null) {
            return self::empty();
        }

        return new self($value);
    }

    /**
     * @param T $value
     */
    public function __construct(
        private readonly mixed $value = null,
    ) {
    }

    /**
     * @return T
     */
    public function get(): mixed
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException('No value present');
        }

        return $this->value;
    }

    public function isPresent(): bool
    {
        return $this->value !== null;
    }

    public function isEmpty(): bool
    {
        return $this->value === null;
    }

    /**
     * @param callable(T): bool $predicate
     * @return self<T>|EmptyOptional
     */
    public function filter(callable $predicate): self
    {
        if ($this->isEmpty()) {
            return $this;
        }

        return $predicate($this->value) ? $this : self::empty();
    }

    /**
     * @template U
     * @param callable(T): U $mapper
     * @return self<U>|EmptyOptional
     */
    public function map(callable $mapper): self
    {
        if ($this->isEmpty()) {
            return self::empty();
        }

        return self::ofNullable($mapper($this->value));
    }

    public function equals(mixed $object): bool
    {
        if ($this === $object) {
            return true;
        }

        return $object instanceof self && $this->value === $object->value;
    }
}
