<?php

namespace JDecool\DataStructure\Tests;

use Exception;
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
    public function orShouldReturnOptionalWhenOptionalHasValue(): void
    {
        $optional = Optional::of('foo');
        $other = Optional::of('bar');

        static::assertSame($optional, $optional->or($other));
    }

    #[Test]
    public function orShouldReturnOtherOptionalWhenOptionalIsEmpty(): void
    {
        $optional = Optional::empty();

        static::assertEquals(Optional::of('bar'), $optional->or('bar'));
    }

    #[Test]
    public function orElseShouldReturnOptionalValueWhenOptionalHasValue(): void
    {
        $optional = Optional::of('foo');

        static::assertSame('foo', $optional->orElse('bar'));
    }

    #[Test]
    public function orElseShoudlReturnElseValueWhenOptionalIsEmpty(): void
    {
        $optional = Optional::empty();

        static::assertSame('bar', $optional->orElse('bar'));
    }

    #[Test]
    public function orElseThrowShouldReturnOptionalValueWhenOptionalHasValue(): void
    {
        $optional = Optional::of('foo');

        static::assertSame('foo', $optional->orElseThrow(new \Exception('No value present')));
    }

    #[Test]
    public function orElseThrowShouldThrowExceptionWhenOptionalIsEmpty(): void
    {
        $optional = Optional::empty();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No value present');

        $optional->orElseThrow(new Exception('No value present'));
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

    #[Test]
    public function ifPresentShouldExecuteCallableIfOptionalContainsValue(): void
    {
        $optional = Optional::of('bar');

        $isExecuted = false;

        $optional->ifPresent(function (string $value) use (&$isExecuted) {
            static::assertSame('bar', $value);

            $isExecuted = true;
        });

        static::assertTrue($isExecuted);
    }

    #[Test]
    public function ifPresentShouldNotExecuteCallableIfOptionalIsNotPresent(): void
    {
        $optional = Optional::ofNullable(null);

        $isExecuted = false;

        $optional->ifPresent(function (string $value) use (&$isExecuted) {
            $isExecuted = true;
        });

        static::assertFalse($isExecuted);
    }
}
