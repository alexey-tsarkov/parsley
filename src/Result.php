<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley;

/**
 * Immutable container for parsed value and its stream position
 *
 * @template-covariant Value
 *
 * @implements Functor<Value>
 */
final readonly class Result implements Functor
{
    /**
     * Factory method creating Result from value and offset
     *
     * @template FromValue
     *
     * @param FromValue $value
     * @phpstan-param non-negative-int $offset
     *
     * @return self<FromValue>
     */
    #[\NoDiscard]
    public static function from(mixed $value, int $offset): self
    {
        return new self($value, $offset);
    }

    /**
     * @param Value $value Parsed value from the input stream
     * @param int $offset Position in the input stream
     * @phpstan-param non-negative-int $offset
     */
    public function __construct(
        public mixed $value,
        public int $offset,
    ) {
        /**
         * @phpstan-ignore smaller.alwaysFalse
         */
        if ($offset < 0) {
            throw new \OutOfRangeException("Offset {$offset} is out of range");
        }
    }

    /**
     * Replaces value, preserves offset
     *
     * @template NewValue
     *
     * @param NewValue $value
     *
     * @return self<NewValue>
     */
    #[\NoDiscard]
    public function as(mixed $value): self
    {
        return new self($value, $this->offset);
    }

    /**
     * Transforms value via function, preserves offset
     *
     * @template NewValue
     *
     * @param callable(Value $value): NewValue $function
     * @param-immediately-invoked-callable $function
     *
     * @return self<NewValue>
     */
    #[\NoDiscard]
    public function map(callable $function): self
    {
        return new self($function($this->value), $this->offset);
    }
}
