<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley\Unit;

use AlexTsarkov\Parsley\Result;
use Testo\Assert;
use Testo\Assert\ExpectException;
use Testo\Assert\ExpectNoAssertions;
use Testo\Codecov\Covers;
use Testo\Data\DataProvider;
use Testo\Filter\Group;
use Testo\Test;

#[Group('Result')]
#[Covers(Result::class)]
final class ResultTest
{
    /**
     * @return iterable<string, array{mixed, int}>
     */
    public static function differentValueTypes(): iterable
    {
        return [
            'null' => [null, 0],
            'false' => [false, 1],
            'true' => [true, 3],
            'int' => [42, 5],
            'float' => [\M_PI, 8],
            'string' => ['hypertext preprocessor', 11],
            'array' => [['php', 1994], 14],
            'object' => [new \stdClass(), 17],
            'callable' => [\phpinfo(...), 21],
        ];
    }

    /**
     * @return iterable<string, array{int}>
     */
    public static function zeroOrPositiveOffsets(): iterable
    {
        return [
            'zero' => [0],
            'positive' => [1],
            'max positive' => [\PHP_INT_MAX],
        ];
    }

    /**
     * @return iterable<string, array{int}>
     */
    public static function negativeOffsets(): iterable
    {
        return [
            'negative' => [-1],
            'min negative' => [\PHP_INT_MIN],
        ];
    }


    #[Test]
    #[DataProvider(self::differentValueTypes(...))]
    public function canCreateWithConstructor(mixed $value, int $offset): void
    {
        $actual = new Result($value, $offset);

        Assert::same($actual->value, $value);
        Assert::same($actual->offset, $offset);
    }

    #[Test]
    #[DataProvider(self::differentValueTypes(...))]
    public function canCreateWithFactory(mixed $value, int $offset): void
    {
        $actual = Result::from($value, $offset);

        Assert::same($actual->value, $value);
        Assert::same($actual->offset, $offset);
    }

    #[Test]
    #[DataProvider(self::differentValueTypes(...))]
    public function factoryAndConstructorAreConsistent(mixed $value, int $offset): void
    {
        $expected = new Result($value, $offset);

        $actual = Result::from($value, $offset);

        Assert::equals($actual, $expected);
        Assert::same($actual->value, $expected->value);
        Assert::same($actual->offset, $expected->offset);
    }

    #[Test]
    #[ExpectNoAssertions]
    #[DataProvider(self::zeroOrPositiveOffsets(...))]
    public function offsetCanBeZeroOrPositive(int $offset): void
    {
        new Result('', $offset);
    }

    #[Test]
    #[ExpectException(\OutOfRangeException::class)]
    #[DataProvider(self::negativeOffsets(...))]
    public function offsetCannotBeNegative(int $offset): void
    {
        new Result('', $offset);
    }

    #[Test]
    public function asReplacesValueButPreservesOffset(): void
    {
        $original = new Result('foo', 1);

        $actual = $original->as('bar');

        Assert::same($actual->value, 'bar');
        Assert::same($actual->offset, 1);
        Assert::notSame($actual, $original);
    }

    #[Test]
    public function mapTransformsValueButPreservesOffset(): void
    {
        $original = new Result(5, 5);

        $actual = $original->map(static fn($v) => $v * 2);

        Assert::same($actual->value, 10);
        Assert::same($actual->offset, 5);
        Assert::notSame($actual, $original);
    }

    #[Test]
    public function mapFunctionReceivesCurrentValue(): void
    {
        $original = new Result('foo', 0);

        (void) $original->map(static function ($value) {
            Assert::same($value, 'foo');

            return $value;
        });
    }
}
