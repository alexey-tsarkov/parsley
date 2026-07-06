<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley\Stream;

use AlexTsarkov\Parsley\Stream;

/**
 * @template Token
 * @extends Stream<Token>
 */
class ArrayStream extends Stream
{
    /**
     * @var Token[]
     */
    private array $input;

    private int $offset = 0;

    /**
     * @param array<Token> $tokens
     * @return self<Token>
     */
    public static function from(array $tokens): self
    {
        return new self($tokens);
    }

    /**
     * @return self<non-empty-string>
     */
    public static function fromString(string $input): self
    {
        return new self(\str_split($input, 1));
    }

    /**
     * @return self<non-empty-string>
     */
    public static function fromMbString(string $input, ?string $encoding = null): self
    {
        return new self(\mb_str_split($input, 1, $encoding));
    }

    /**
     * @param Token ...$tokens
     * @return self<Token>
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
            throw new \OutOfBoundsException("Offset {$this->offset} is out of range");
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
        return $this->offset >= 0 && $this->offset < \count($this->input);
    }

    #[\Override]
    public function seek(mixed $offset): void
    {
        $this->offset = $offset;
    }
}
