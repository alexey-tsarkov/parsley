<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley\Unit\Stream;

use AlexTsarkov\Parsley\Stream\ArrayStream;
use Testo\Assert;
use Testo\Assert\ExpectException;
use Testo\Codecov\Covers;
use Testo\Filter\Group;
use Testo\Test;

#[Group('Stream')]
#[Covers(ArrayStream::class)]
final class ArrayStreamTest
{
    #[Test]
    public function canCreateWithFactory(): void
    {
        $stream = ArrayStream::from([1 => 1, 1, 2, 3, 5, 8]);

        Assert::same(\iterator_to_array($stream, true), [1, 1, 2, 3, 5, 8]);
    }

    #[Test]
    public function canCreateFromString(): void
    {
        $stream = ArrayStream::fromString('hello');

        Assert::same(\iterator_to_array($stream, true), ['h', 'e', 'l', 'l', 'o']);
    }

    #[Test]
    public function canCreateFromMbString(): void
    {
        $stream = ArrayStream::fromMbString('привет', 'UTF-8');

        Assert::same(\iterator_to_array($stream, true), ['п', 'р', 'и', 'в', 'е', 'т']);
    }

    #[Test]
    public function canCreateFromListOfTokens(): void
    {
        $stream = ArrayStream::of(int: 42, float: 3.14, bool: true, null: null, string: 'test');

        Assert::same(\iterator_to_array($stream, true), [42, 3.14, true, null, 'test']);
    }

    #[Test]
    public function canCreateWithConstructor(): void
    {
        $stream = new ArrayStream(['hola']);

        Assert::same(\iterator_to_array($stream, true), ['hola']);
    }

    #[Test]
    public function canIterateOverStream(): void
    {
        $stream = new ArrayStream(['a', 'b', 'c']);

        $stream->rewind();

        Assert::true($stream->valid());
        Assert::same($stream->key(), 0);
        Assert::same($stream->current(), 'a');

        $stream->next();

        Assert::true($stream->valid());
        Assert::same($stream->key(), 1);
        Assert::same($stream->current(), 'b');

        $stream->next();

        Assert::true($stream->valid());
        Assert::same($stream->key(), 2);
        Assert::same($stream->current(), 'c');

        $stream->next();

        Assert::false($stream->valid());
        Assert::same($stream->key(), 3);
    }

    #[Test]
    #[ExpectException(\UnderflowException::class)]
    public function currentThrowsWhenStreamIsEmpty(): void
    {
        $stream = new ArrayStream([]);

        Assert::false($stream->valid());
        Assert::same($stream->key(), 0);
        $_ = $stream->current();
    }

    #[Test]
    #[ExpectException(\UnderflowException::class)]
    public function currentThrowsWhenAtEnd(): void
    {
        $stream = new ArrayStream(['a', 'b', 'c']);
        $stream->seek(3);

        Assert::false($stream->valid());
        Assert::same($stream->key(), 3);
        $_ = $stream->current();
    }

    #[Test]
    #[ExpectException(\InvalidArgumentException::class)]
    public function seekThrowsForNonIntegerOffset(): void
    {
        $stream = new ArrayStream(['a', 'b', 'c']);

        /**
         * @phpstan-ignore argument.type
         */
        $stream->seek('1');
    }

    #[Test]
    #[ExpectException(\OutOfRangeException::class)]
    public function seekThrowsForNegativeOffset(): void
    {
        $stream = new ArrayStream(['a', 'b', 'c']);

        /**
         * @phpstan-ignore argument.type
         */
        $stream->seek(-1);
    }

    #[Test]
    public function nextCanAdvanceBeyondStreamEnd(): void
    {
        $stream = new ArrayStream(['a']);

        $stream->next();

        Assert::false($stream->valid());
        Assert::same($stream->key(), 1);

    }

    #[Test]
    public function seekCanAdvanceBeyondStreamEnd(): void
    {
        $stream = new ArrayStream(['a']);

        $stream->seek(1);

        Assert::false($stream->valid());
        Assert::same($stream->key(), 1);
    }

    #[Test]
    public function rewindResetsStream(): void
    {
        $stream = new ArrayStream(['a', 'b', 'c']);
        $stream->seek(2);
        Assert::same($stream->key(), 2);

        $stream->rewind();

        Assert::same($stream->key(), 0);
    }

    #[Test]
    public function peekReturnsNullWhenStreamIsEmpty(): void
    {
        $stream = new ArrayStream([]);

        Assert::null($stream->peek());
    }

    #[Test]
    public function peekReturnsNullWhenAtEnd(): void
    {
        $stream = new ArrayStream(['a']);
        $stream->next();

        Assert::null($stream->peek());
    }

    #[Test]
    public function peekReturnsCurrentResult(): void
    {
        $stream = new ArrayStream(['a', 'b', 'c']);
        $stream->next();

        $result = $stream->peek();

        Assert::notNull($result);
        Assert::same($result->value, 'b');
        Assert::same($result->offset, 1);
    }

    #[Test]
    public function asReplacesTokens(): void
    {
        $stream = new ArrayStream(['a', 'b', 'c'])->as(5);

        Assert::same(\iterator_to_array($stream, true), [5, 5, 5]);
    }

    #[Test]
    public function mapTransformsTokens(): void
    {
        $stream = new ArrayStream(['a', 'bb', 'ccc'])->map(\strlen(...));

        Assert::same(\iterator_to_array($stream, true), [1, 2, 3]);
    }
}
