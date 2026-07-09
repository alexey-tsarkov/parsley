<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley\Stream;

use AlexTsarkov\Parsley\Stream;

/**
 * A Stream implementation that operates on arrays of tokens
 *
 * @template Token = string
 *
 * @extends Stream<Token>
 */
class ArrayStream extends Stream
{
    /**
     * @var list<Token>
     */
    private array $input;

    /**
     * @phpstan-var non-negative-int
     */
    private int $offset = 0;

    /**
     * Factory method creating Stream from array of tokens
     *
     * @template FromToken
     *
     * @param array<FromToken> $tokens
     *
     * @return self<FromToken>
     */
    public static function from(array $tokens): self
    {
        return new self($tokens);
    }

    /**
     * Factory method creating Stream from bytes of the string
     *
     * @return self<string>
     * @phpstan-return self<non-empty-string>
     */
    public static function fromString(string $input): self
    {
        return new self(\str_split($input, 1));
    }

    /**
     * Factory method creating Stream from multibyte characters of the string
     *
     * @return self<string>
     * @phpstan-return self<non-empty-string>
     */
    public static function fromMbString(string $input, ?string $encoding = null): self
    {
        return new self(\mb_str_split($input, 1, $encoding));
    }

    /**
     * Factory method creating Stream from variadic list of tokens
     *
     * @template FromToken
     *
     * @param FromToken ...$tokens
     *
     * @return self<FromToken>
     */
    public static function of(mixed ...$tokens): self
    {
        return new self($tokens);
    }

    /**
     * @param array<Token> $input
     */
    public function __construct(array $input)
    {
        $this->input = \array_values($input);
    }

    #[\Override]
    public function current(): mixed
    {
        if (!$this->valid()) {
            throw new \UnderflowException("Cannot access token at offset {$this->offset}");
        }

        return $this->input[$this->offset];
    }

    #[\Override]
    public function key(): int
    {
        return $this->offset;
    }

    #[\Override]
    public function next(): void
    {
        $this->offset++;
    }

    #[\Override]
    public function rewind(): void
    {
        $this->offset = 0;
    }

    #[\Override]
    public function valid(): bool
    {
        return $this->offset < \count($this->input);
    }

    #[\Override]
    public function seek(mixed $offset): void
    {
        /**
         * @phpstan-ignore function.alreadyNarrowedType
         */
        if (!\is_int($offset)) {
            $type = \get_debug_type($offset);

            throw new \InvalidArgumentException("Offset must be of type int, {$type} given");
        }

        /**
         * @phpstan-ignore smaller.alwaysFalse
         */
        if ($offset < 0) {
            throw new \OutOfRangeException("Offset {$offset} is out of range");
        }

        $this->offset = $offset;
    }
}
