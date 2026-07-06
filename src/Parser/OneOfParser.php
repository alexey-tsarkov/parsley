<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley\Parser;

use AlexTsarkov\Parsley;

/**
 * @template Token
 * @extends Parsley\Parser<Token, Token>
 */
final readonly class OneOfParser extends Parsley\Parser
{
    /**
     * @var array<Token>
     */
    private array $tokens;

    /**
     * @param Token ...$tokens
     */
    public function __construct(mixed ...$tokens)
    {
        $this->tokens = $tokens;
    }

    #[\Override]
    public function parse(Parsley\Stream $stream): ?Parsley\Result
    {
        $result = $stream->peek();
        if ($result && \in_array($result->value, $this->tokens, true)) {
            $stream->next();

            return $result;
        }

        return null;
    }
}
