<?php

declare(strict_types=1);

namespace AlexTsarkov\Parsley;

/**
 * A container that can be mapped over
 *
 * @template-covariant Type
 *
 * @internal
 */
interface Functor
{
    /**
     * Replaces all values with a constant value
     *
     * @template NewType
     *
     * @param NewType $value
     *
     * @return self<NewType>
     */
    public function as(mixed $value): self;

    /**
     * Applies a function to the contained values
     *
     * @template NewType
     *
     * @param callable(Type $x): NewType $function
     *
     * @return self<NewType>
     */
    public function map(callable $function): self;
}
