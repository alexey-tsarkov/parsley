<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley\Parser;

use AlexTsarkov\Parsley;
use AlexTsarkov\Parsley\Parser;

/**
 * @template Value
 * @extends Parsley\Parser<never, Value>
 */
final readonly class SuccessParser extends Parsley\Parser
{
    /**
     * @param Value $value
     */
    public function __construct(private mixed $value) {}

    #[\Override]
    public function parse(Parsley\Stream $stream): Parsley\Result
    {
        return new Parsley\Result($this->value, $stream->key());
    }

    #[\Override]
    public function as(mixed $value): Parser
    {
        return new self($value);
    }
}
