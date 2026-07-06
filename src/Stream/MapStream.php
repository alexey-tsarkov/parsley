<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley\Stream;

use AlexTsarkov\Parsley\Stream;

/**
 * @template Token
 * @template NewToken
 * @implements Stream<NewToken>
 */
final class MapStream extends Stream
{
    /**
     * @var \Closure(Token $token): NewToken
     */
    private readonly \Closure $function;

    /**
     * @param Stream<Token> $stream
     * @param callable(Token $token): NewToken $function
     */
    public function __construct(
        private readonly Stream $stream,
        callable $function,
    ) {
        $this->function = \Closure::fromCallable($function);
    }

    #[\Override]
    public function current(): mixed
    {
        return ($this->function)($this->stream->current());
    }

    #[\Override]
    public function key(): int
    {
        return $this->stream->key();
    }

    #[\Override]
    public function next(): void
    {
        $this->stream->next();
    }

    #[\Override]
    public function rewind(): void
    {
        $this->stream->rewind();
    }

    #[\Override]
    public function valid(): bool
    {
        return $this->stream->valid();
    }

    #[\Override]
    public function seek(mixed $offset): void
    {
        $this->stream->seek($offset);
    }
}
