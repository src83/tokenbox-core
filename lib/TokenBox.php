<?php

declare(strict_types=1);

namespace lib;

use src\DTO\Generator\ConfigDTO;

class TokenBox
{
    public const counterLen = 2;
    public const BASE = 62;
    public const boxFolder = 'data/txt/';

    private array $box;
    private Base62 $base62;

    private int $index;
    private int $SIDE;
    private int $offset;
    private int $quadCurrent;
    private bool $calcCRC;

    private int $sumBeforeMix;
    private int $sumAfterMix;

    private string $boxFile;

    public function __construct(ConfigDTO $configDTO)
    {
        $this->box = [];
        $this->base62 = new Base62();

        $this->index = $configDTO->index;
        $this->SIDE = $configDTO->side;
        $this->offset = $configDTO->offset;
        $this->quadCurrent = $configDTO->quadCurrent;
        $this->calcCRC = $configDTO->calcCRC;

        $this->sumBeforeMix = 0;
        $this->sumAfterMix = 0;

        $this->boxFile = $this->getFilename();
    }

    // -----------------------------------------------------------------------------

    public function makeBox(): void
    {
        $nomerProhoda = 1;    // № линии (стартовый)

        while ($nomerProhoda <= $this->SIDE) {

            $this->box[] = range(
                ($nomerProhoda - 1) * $this->SIDE + $this->offset,
                $nomerProhoda * $this->SIDE - 1 + $this->offset
            );

            $nomerProhoda++;
        }

        if ($this->calcCRC) {
            $this->sumBeforeMix = $this->sumBox();
        }
    }

    // -----------------------------------------------------------------------------

    public function mixBox(): void
    {
        // horizontal
        foreach($this->box as &$line) {
            shuffle($line);
        }

        // vertical
        for($c = 0; $c < $this->SIDE; $c++) {
            $column = array_column($this->box, $c);
            shuffle($column);
            $this->replaceColumn($column, $c);
        }

        if ($this->calcCRC) {
            $this->sumAfterMix = $this->sumBox();
        }
    }

    private function replaceColumn($column, $columnIndex): void
    {
        foreach ($this->box as $key => $line) {
            if (isset($column[$key])) {
                $this->box[$key][$columnIndex] = $column[$key];
            }
        }
    }

    // -----------------------------------------------------------------------------

    public function hashBox(): void
    {
        foreach($this->box as $i => $line) {
            $this->box[$i] = array_map(function ($number) {
                return $this->base62::convert($number);
            }, $line);
        }
    }

    // -----------------------------------------------------------------------------

    private function sumBox(): int
    {
        $sum = 0;
        foreach ($this->box as $line) {
            $sum += array_sum($line);
        }
        return $sum;
    }

    // -----------------------------------------------------------------------------

    public function calcCRC(): string
    {
        if ($this->sumBeforeMix === $this->sumAfterMix) {
            return 'valid';
        }
        return 'NOT valid';
    }

    // -----------------------------------------------------------------------------

    // Использовать только в сочетании с hashBox();
    public function cutBox(): void
    {
        $flag = 0;
        foreach ($this->box as &$line) {
            foreach($line as $k => $v) {

                $v = (string)$v;

                if (strlen($v) !== $this->index) {
                    unset($line[$k]);
                }

                if ($flag === 1) {
                    unset($line[$k]);
                }

                if ($v === '15FTGf' && strlen($v) === 6) {
                    $flag = 1;
                }

            }
        }
    }

    // -----------------------------------------------------------------------------

    public function printBox(): void
    {
        echo '   ';
        for($c = 0; $c < $this->SIDE; $c++) {
            echo sprintf("%3s", $c);
        } echo PHP_EOL . '   ';
        for($c = 0; $c < $this->SIDE; $c++) {
            echo sprintf("%3s", '-');
        } echo PHP_EOL;

        foreach($this->box as $i => $line) {
            echo sprintf("%2s:", $i);
            foreach($line as $k => $v) {
                echo sprintf("%3s", $v);
            }
            echo PHP_EOL;
        }
    }

    // -----------------------------------------------------------------------------

    public function saveBox(): void
    {
        if (file_exists($this->boxFile)) {
            unlink($this->boxFile);
        }
        foreach ($this->box as $line) {
            $data = implode("\n", $line) . PHP_EOL;
            file_put_contents($this->boxFile, $data, FILE_APPEND);
        }
    }

    // -----------------------------------------------------------------------------

    public function getFilename(): string
    {
        $quadCurrent = (string)$this->quadCurrent;

        $len = strlen($quadCurrent);
        $delta = self::counterLen - $len;

        $counter = ($delta === 0) ? $quadCurrent : str_repeat('0', $delta) . $quadCurrent;

        return self::boxFolder.'box_L'.$this->index.'_'.$this->SIDE.'x'.$this->SIDE.'_'.$counter.'.txt';
    }

    // -----------------------------------------------------------------------------

    public function printStat(): void
    {
        $timeEnd = microtime(true);

        $stat = [
            'time_total' => round($timeEnd - TIME_START, 6),
            'memory_usage' => round(memory_get_peak_usage(true) / 1048576, 6),
        ];

        if ($this->calcCRC) {
            $stat['crc_is'] = $this->calcCRC();
        }

        print_r($stat);
        echo "\n";

    }

    // -----------------------------------------------------------------------------

}
