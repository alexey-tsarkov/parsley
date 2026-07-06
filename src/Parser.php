<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley;

/**
 * @template Token
 * @template Value
 * @implements Functor<Value>
 */
abstract readonly class Parser implements Functor
{
    /**
     * @param Stream<Token> $stream
     * @return ?Result<Value>
     */
    abstract public function parse(Stream $stream): ?Result;

    /**
     * @template NewValue
     * @param NewValue $value
     * @return self<Token, NewValue>
     *
     */
    public function as(mixed $value): self
    {
        return new Parser\AsParser($this, $value);
    }

    /**
     * @template NewValue
     * @param callable(Value $value): NewValue $function
     * @return self<Token, NewValue>
     */
    public function map(callable $function): self
    {
        return new Parser\MapParser($this, $function);
    }
}
