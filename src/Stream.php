<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley;

/**
 * A seekable stream of tokens
 *
 * @template Token
 * @implements \SeekableIterator<int, Token>
 * @implements Functor<Token>
 */
abstract class Stream implements \SeekableIterator, Functor
{
    /**
     * Creates a new stream instance with different input
     *
     * @return static<Token>
     */
    #[\NoDiscard]
    abstract public function withInput(mixed $input): static;

    /**
     * Returns the current token without advancing the pointer
     *
     * @return Token
     * @throws \OutOfBoundsException
     */
    #[\Override]
    #[\NoDiscard]
    abstract public function current(): mixed;

    /**
     * Returns the current position in the stream
     *
     * @return int
     */
    #[\Override]
    #[\NoDiscard]
    abstract public function key(): int;

    /**
     * Advances the stream pointer to the next token
     */
    #[\Override]
    abstract public function next(): void;

    /**
     * Resets the stream pointer to the first token
     */
    #[\Override]
    abstract public function rewind(): void;

    /**
     * Checks if the current position is valid (has a token)
     */
    #[\Override]
    #[\NoDiscard]
    abstract public function valid(): bool;

    /**
     * Moves the stream pointer to a specific position
     *
     * @param int $offset
     */
    #[\Override]
    abstract public function seek(mixed $offset): void;

    #[\Override]
    #[\NoDiscard]
    final public function as(mixed $value): self
    {
        return new Stream\AsStream($this, $value);
    }

    #[\Override]
    #[\NoDiscard]
    final public function map(callable $function): self
    {
        return new Stream\MapStream($this, $function);
    }
}
