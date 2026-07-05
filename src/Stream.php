<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley;

/**
 * A seekable stream of tokens
 *
 * @template Token
 * @extends \SeekableIterator<int, Token>
 * @extends Functor<Token>
 */
interface Stream extends \SeekableIterator, Functor
{
    /**
     * Creates a new stream instance with different input
     *
     * @return static<Token>
     */
    #[\NoDiscard]
    public function withInput(mixed $input): static;

    /**
     * Returns the current token without advancing the pointer
     *
     * @return Token
     * @throws \OutOfBoundsException
     */
    #[\Override]
    #[\NoDiscard]
    public function current(): mixed;

    /**
     * Returns the current position in the stream
     *
     * @return int
     */
    #[\Override]
    #[\NoDiscard]
    public function key(): int;

    /**
     * Advances the stream pointer to the next token
     */
    #[\Override]
    public function next(): void;

    /**
     * Resets the stream pointer to the first token
     */
    #[\Override]
    public function rewind(): void;

    /**
     * Checks if the current position is valid (has a token)
     */
    public function valid(): bool;

    /**
     * Moves the stream pointer to a specific position
     *
     * @param int $offset
     */
    #[\Override]
    public function seek(mixed $offset, int $whence = \SEEK_SET): void;
}
