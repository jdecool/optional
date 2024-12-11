<?php

declare(strict_types=1);

namespace JDecool\DataStructure;

use LogicException;
use Throwable;

/**
 * @template T
 */
class Optional
{
    /**
     * @return self<null>
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
     * @return (V is null ? self<null> : self<V>)
     */
    public static function ofNullable(mixed $value): self
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
     * @throws NoSuchElementException
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
     * @return self<T>|self<null>
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
     * @return (T is null ? self<null> : self<U>)
     */
    public function map(callable $mapper): self
    {
        if ($this->isEmpty()) {
            return self::empty();
        }

        return self::ofNullable($mapper($this->value));
    }

    /**
     * @template U
     * @param U $other
     * @return (T is null ? self<U> : self<T>)
     * @throws LogicException
     */
    public function or(mixed $other): self
    {
        if ($this->isPresent()) {
            return $this;
        }

        if ($other === null) {
            throw new LogicException('Value cannot be null');
        }

        return self::of($other); // @phpstan-ignore-line
    }

    /**
     * @template U
     * @param U $other
     * @return (T is null ? U : T)
     */
    public function orElse(mixed $other): mixed
    {
        return $this->isEmpty() ? $other : $this->value;
    }

    /**
     * @template U
     * @param callable(): U $supplier
     * @return (T is null ? U : T)
     */
    public function orElseGet(callable $supplier): mixed
    {
        return $this->isEmpty() ? $supplier() : $this->value;
    }

    /**
     * @template TException of Throwable
     * @param TException $exception
     * @return T
     * @throw TException
     */
    public function orElseThrow(Throwable $exception = new NoSuchElementException()): mixed
    {
        if ($this->isEmpty()) {
            throw $exception;
        }

        return $this->value;
    }

    public function equals(mixed $object): bool
    {
        if ($this === $object) {
            return true;
        }

        return $object instanceof self && $this->value === $object->value;
    }

    /**
     * @param callable(T): void $action
     */
    public function ifPresent(callable $action): void
    {
        if ($this->isPresent()) {
            $action($this->value);
        }
    }

    /**
     * @param callable(T): void $action
     * @param callable(): void  $emptyAction
     */
    public function ifPresentOrElse(callable $action, callable $emptyAction): void
    {
        if ($this->isPresent()) {
            $action($this->value);
        } else {
            $emptyAction();
        }
    }
}
