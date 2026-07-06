<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley\Parser;

use AlexTsarkov\Parsley;

/**
 * @template Token
 * @template Value
 * @template NewValue
 * @extends Parsley\Parser<Token, NewValue>
 */
final readonly class MapParser extends Parsley\Parser
{
    /**
     * @var \Closure(Value $value): NewValue
     */
    private \Closure $function;

    /**
     * @param Parsley\Parser<Token, Value> $parser
     * @param callable(Value $value): NewValue $function
     */
    public function __construct(
        private Parsley\Parser $parser,
        callable $function,
    ) {
        $this->function = \Closure::fromCallable($function);
    }

    /**
     * @param Parsley\Stream<Token> $stream
     * @return ?Parsley\Result<NewValue>
     */
    #[\Override]
    public function parse(Parsley\Stream $stream): ?Parsley\Result
    {
        return $this->parser->parse($stream)?->map($this->function);
    }
}
