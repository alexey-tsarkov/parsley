<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley;

/**
 * A seekable stream of tokens
 *
 * @template Token = string
 *
 * @implements \SeekableIterator<int, Token>
 * @phpstan-implements \SeekableIterator<non-negative-int, Token>
 * @implements Functor<Token>
 */
abstract class Stream implements \SeekableIterator, Functor
{
    /**
     * Returns the current token without advancing the pointer
     *
     * @return Token
     *
     * @throws \UnderflowException
     */
    #[\Override]
    #[\NoDiscard]
    abstract public function current(): mixed;

    /**
     * Returns the current position in the stream
     *
     * @phpstan-return non-negative-int
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
     * @phpstan-param non-negative-int $offset
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfRangeException
     */
    #[\Override]
    abstract public function seek(mixed $offset): void;

    /**
     * Creates a new stream with all tokens replaced by a constant value
     *
     * @template NewToken
     *
     * @param NewToken $token
     *
     * @return self<NewToken>
     */
    #[\Override]
    #[\NoDiscard]
    public function as(mixed $token): self
    {
        return new Stream\AsStream($this, $token);
    }

    /**
     * Creates a new stream by applying a transformation function to each token
     *
     * @template NewToken
     *
     * @param callable(Token $token): NewToken $function
     *
     * @return self<NewToken>
     */
    #[\Override]
    #[\NoDiscard]
    public function map(callable $function): self
    {
        return new Stream\MapStream($this, $function);
    }

    /**
     * Safely retrieves the current token as a Result object without advancing the pointer
     *
     * @return ?Result<Token>
     */
    #[\NoDiscard]
    final public function peek(): ?Result
    {
        if ($this->valid()) {
            return new Result($this->current(), $this->key());
        }

        return null;
    }
}
