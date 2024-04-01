<?php

declare(strict_types=1);

namespace lib;

use src\DTO\Calc\ConfigDTO;

class Calc
{
    private Base62 $base62;
    private int $fromBase;
    private int $toBase;
    private mixed $value;
    private mixed $result;

    // -----------------------------------------------------------------------------

    public function __construct(ConfigDTO $confDTO)
    {
        $this->base62 = new Base62();
        $this->fromBase = $confDTO->fromBase;
        $this->toBase = $confDTO->toBase;
        $this->value = $confDTO->value;
    }

    // -----------------------------------------------------------------------------

    public function makeCalc(): mixed
    {
        $this->result = $this->base62::convert($this->value, $this->fromBase, $this->toBase);

        return $this->result;
    }

    // -----------------------------------------------------------------------------

    public function printResult(): void
    {
        print_r($this->value . ' [' . $this->fromBase .' => '. $this->toBase . '] >> ' . $this->result);

        echo "\n";
    }

    // -----------------------------------------------------------------------------

    public function printStat(): void
    {
        $timeEnd = microtime(true);

        $stat = [
            'time_total' => round($timeEnd - TIME_START, 6),
            'memory_usage' => round(memory_get_peak_usage(true) / 1048576, 6),
        ];

        print_r($stat);
        echo "\n";
    }

    // -----------------------------------------------------------------------------

}
