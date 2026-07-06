<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley\Stream;

use AlexTsarkov\Parsley\Stream;

/**
 * @template Token
 * @extends Stream<Token>
 */
final class AsStream extends Stream
{
    /**
     * @param Stream<*> $stream
     * @param Token $value
     */
    public function __construct(
        private Stream $stream,
        private mixed $value,
    ) {
        if ($stream instanceof self) {
            $this->stream = $stream->stream;
        }
    }

    #[\Override]
    public function current(): mixed
    {
        (void) $this->stream->current();

        return $this->value;
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
