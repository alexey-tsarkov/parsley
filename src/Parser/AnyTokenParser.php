<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley\Parser;

use AlexTsarkov\Parsley;

/**
 * @template Token
 * @extends Parsley\Parser<Token, Token>
 */
final readonly class AnyTokenParser extends Parsley\Parser
{
    #[\Override]
    public function parse(Parsley\Stream $stream): ?Parsley\Result
    {
        $result = $stream->peek();
        if ($result) {
            $stream->next();
        }

        return $result;
    }
}
