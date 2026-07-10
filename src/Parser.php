<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley;

/**
 * A parser that parses values from a token stream
 *
 * @template Token
 * @template Value
 *
 * @implements Functor<Value>
 */
abstract readonly class Parser implements Functor
{
    /**
     * Attempts to parse a value from the current stream position
     *
     * @param Stream<Token> $stream
     *
     * @return ?Result<Value>
     */
    abstract public function parse(Stream $stream): ?Result;

    /**
     * Alias for parse()
     *
     * @param Stream<Token> $stream
     *
     * @return ?Result<Value>
     */
    final public function __invoke(Stream $stream): ?Result
    {
        return $this->parse($stream);
    }

    /**
     * Replaces the parsed values with a constant
     *
     * @template NewValue
     *
     * @param NewValue $value
     *
     * @return self<Token, NewValue>
     *
     */
    public function as(mixed $value): self
    {
        return new Parser\AsParser($this, $value);
    }

    /**
     * Transforms the parsed values using a function
     *
     * @template NewValue
     *
     * @param callable(Value $value): NewValue $function
     *
     * @return self<Token, NewValue>
     */
    public function map(callable $function): self
    {
        return new Parser\MapParser($this, $function);
    }
}
