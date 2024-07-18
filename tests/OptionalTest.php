<?php

namespace JDecool\DataStructure\Tests;

use JDecool\DataStructure\NoSuchElementException;
use JDecool\DataStructure\Optional;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class OptionalTest extends TestCase
{
    #[Test]
    public function getShouldReturnOptionalValue(): void
    {
        $optional = Optional::of('foo');

        static::assertSame('foo', $optional->get());
    }

    #[Test]
    public function getShouldThrowExceptionWhenOptionalIsEmpty(): void
    {
        $optional = Optional::empty();

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('No value present');

        $optional->get();
    }

    #[Test]
    public function isPresentShouldReturnTrueWhenOptionalHasValue(): void
    {
        $optional = Optional::of('foo');

        static::assertTrue($optional->isPresent());
    }

    #[Test]
    public function isPresentShouldReturnFalseWhenOptionalIsEmpty(): void
    {
        $optional = Optional::empty();

        static::assertFalse($optional->isPresent());
    }

    #[Test]
    public function isEmptyShouldReturnTrueWhenOptionalIsEmpty(): void
    {
        $optional = Optional::empty();

        static::assertTrue($optional->isEmpty());
    }

    #[Test]
    public function isEmptyShouldReturnFalseWhenOptionalContainsValue(): void
    {
        $optional = Optional::of('foo');

        static::assertFalse($optional->isEmpty());
    }

    #[Test]
    public function filterShouldReturnOptionalWhenPredicateIsTrue(): void
    {
        $optional = Optional::of('foo');
        $predicate = static fn ($value) => $value === 'foo';

        static::assertSame($optional, $optional->filter($predicate));
    }

    #[Test]
    public function filterShouldReturnEmptyOptionalWhenPredicateIsFalse(): void
    {
        $optional = Optional::of('foo');
        $predicate = static fn ($value) => $value === 'bar';

        static::assertTrue($optional->filter($predicate)->isEmpty());
    }

    #[Test]
    public function mapShouldReturnOptionalWithTransformedValue(): void
    {
        $optional = Optional::of('foo');
        $mapper = static fn ($value) => strtoupper($value);

        static::assertSame('FOO', $optional->map($mapper)->get());
    }

    #[Test]
    public function mapShouldReturnEmptyOptionalWhenOptionalIsEmpty(): void
    {
        $optional = Optional::empty();
        $mapper = static fn ($value) => strtoupper($value);

        static::assertTrue($optional->map($mapper)->isEmpty());
    }

    #[Test]
    public function equalsShouldReturnTrueWhenCompareSameOptionalInstance(): void
    {
        $optional = Optional::of('foo');

        static::assertTrue($optional->equals($optional));
    }

    #[Test]
    public function equalsShouldReturnTrueWhenCompareDifferentOptionalInstanceWithSameValue(): void
    {
        $optional1 = Optional::of('foo');
        $optional2 = Optional::of('foo');

        static::assertTrue($optional1->equals($optional2));
    }

    #[Test]
    public function equalsShouldReturnFalseWhenCompareDifferentOptionalInstanceWithDifferentValue(): void
    {
        $optional1 = Optional::of('foo');
        $optional2 = Optional::of('bar');

        static::assertFalse($optional1->equals($optional2));
    }

    #[Test]
    public function equalsShouldReturnFalseWhenCompareDifferentOptionalInstanceWithDifferentType(): void
    {
        $optional = Optional::of('foo');

        static::assertFalse($optional->equals('foo'));
    }
}
