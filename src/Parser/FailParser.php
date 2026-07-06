<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley\Parser;

use AlexTsarkov\Parsley;
use AlexTsarkov\Parsley\Parser;

/**
 * @template Token
 * @extends Parsley\Parser<Token, never>
 */
final readonly class FailParser extends Parsley\Parser
{
    #[\Override]
    public function parse(Parsley\Stream $stream): null
    {
        return null;
    }

    #[\Override]
    public function as(mixed $value): Parser
    {
        return $this;
    }

    #[\Override]
    public function map(callable $function): Parser
    {
        return $this;
    }
}
