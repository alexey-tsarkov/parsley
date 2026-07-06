<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley\Parser;

use AlexTsarkov\Parsley;

/**
 * @template Token
 * @template Value
 * @extends Parsley\Parser<Token, Value>
 */
final readonly class AsParser extends Parsley\Parser
{
    /**
     * @param Parsley\Parser<Token, never> $parser
     * @param Value $value
     */
    public function __construct(
        private Parsley\Parser $parser,
        private mixed $value,
    ) {}

    /**
     * @param Parsley\Stream<Token> $stream
     * @return ?Parsley\Result<Value>
     */
    #[\Override]
    public function parse(Parsley\Stream $stream): ?Parsley\Result
    {
        return $this->parser->parse($stream)?->as($this->value);
    }
}
