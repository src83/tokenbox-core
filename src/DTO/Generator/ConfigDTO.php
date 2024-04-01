<?php

declare(strict_types=1);

namespace src\DTO\Generator;

class ConfigDTO
{
    public int $index;
    public int $side;
    public int $offset;
    public int $quadCurrent;
    public bool $calcCRC;

    public function __construct(int $index, int $side, int $offset, int $quadCurrent, bool $calcCRC = false)
    {
        $this->index = $index;
        $this->side = $side;
        $this->offset = $offset;
        $this->quadCurrent = $quadCurrent;
        $this->calcCRC = $calcCRC;
    }
}
