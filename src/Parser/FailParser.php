<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley\Parser;

use AlexTsarkov\Parsley;

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
}
