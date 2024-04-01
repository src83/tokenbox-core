<?php

declare(strict_types=1);

namespace src\DTO\Calc;

class ConfigDTO
{
    public function __construct(public int $fromBase, public int $toBase, public mixed $value)
    {
    }
}
