<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley\Parser;

use AlexTsarkov\Parsley;

/**
 * @template Token
 * @extends Parsley\Parser<Token, Token>
 */
final readonly class TokenParser extends Parsley\Parser
{
    /**
     * @param Token $token
     */
    public function __construct(private mixed $token) {}

    #[\Override]
    public function parse(Parsley\Stream $stream): ?Parsley\Result
    {
        $result = $stream->peek();
        if ($result && $result->value === $this->token) {
            $stream->next();

            return $result;
        }

        return null;
    }
}
