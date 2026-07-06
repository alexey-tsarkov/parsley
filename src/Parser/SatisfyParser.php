<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley\Parser;

use AlexTsarkov\Parsley;

/**
 * @template Token
 * @extends Parsley\Parser<Token, Token>
 */
final readonly class SatisfyParser extends Parsley\Parser
{
    /**
     * @var \Closure(Token $token): bool
     */
    private \Closure $predicate;

    /**
     * @param callable(Token $token): bool $predicate
     */
    public function __construct(callable $predicate)
    {
        $this->predicate = \Closure::fromCallable($predicate);
    }

    #[\Override]
    public function parse(Parsley\Stream $stream): ?Parsley\Result
    {
        $result = $stream->peek();
        if ($result && ($this->predicate)($result->value)) {
            $stream->next();

            return $result;
        }

        return null;
    }
}
