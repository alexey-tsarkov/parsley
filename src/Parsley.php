<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley;

abstract class Parsley
{
    /**
     * @template T
     * @return Parser<T, never>
     * @phpstan-ignore method.templateTypeNotInParameter
     */
    final public static function fail(): Parser
    {
        static $parser = new Parser\FailParser();

        return $parser;
    }

    /**
     * @template V
     * @param V $value
     * @return Parser<never, V>
     */
    final public static function success(mixed $value): Parser
    {
        return new Parser\SuccessParser($value);
    }

    /**
     * @template T
     * @return Parser<T, T>
     * @phpstan-ignore method.templateTypeNotInParameter
     */
    final public static function anyToken(): Parser
    {
        static $parser = new Parser\AnyTokenParser();

        return $parser;
    }

    /**
     * @template T
     * @param callable(T $token): bool $predicate
     * @return Parser<T, T>
     */
    final public static function satisfy(callable $predicate): Parser
    {
        return new Parser\SatisfyParser($predicate);
    }

    /**
     * @template T
     * @param T $token
     * @return Parser<T, T>
     */
    final public static function token(mixed $token): Parser
    {
        return new Parser\TokenParser($token);
    }

    /**
     * @template T
     * @param T ...$tokens
     * @return Parser<T, T>
     */
    final public static function oneOf(mixed ...$tokens): Parser
    {
        return new Parser\OneOfParser(...$tokens);
    }

    /**
     * @template T
     * @param T ...$tokens
     * @return Parser<T, T>
     */
    final public static function noneOf(mixed ...$tokens): Parser
    {
        return new Parser\NoneOfParser(...$tokens);
    }
}
