<?php

declare(strict_types=1);

namespace src\Utils\Generator;

use lib\TokenBox;
use src\DTO\Generator\ConfigDTO;

class ConfigProvider
{
    public const base = TokenBox::BASE;
    public const side5k = 5000;

    /**
     * Возвращает одну из возможных конфигураций по индексу (консольному параметру)
     * @param array $argv
     * @return ConfigDTO
     */
    public static function getConfig(array $argv): ConfigDTO
    {
        $index = (int)$argv[1];
        $quadCurrent = (int)$argv[2];

        $configs = [
            1 => [
                'side' => 8,
                'offset' => $index - 1,
            ],
            2 => [
                'side' => self::base,
                'offset' => self::base ** ($index - 1),
            ],
            3 => [
                'side' => 485,
                'offset' => self::base ** ($index - 1),
            ],
            4 => [
                'side' => 3813,
                'offset' => self::base ** ($index - 1),
            ],
            5 => [
                'side' => self::side5k,
                'offset' => self::base ** ($index - 1) + self::side5k * self::side5k * ($quadCurrent - 1),
                'quadCurrent' => $quadCurrent,
            ],
            6 => [
                'side' => self::side5k,
                'offset' => self::base ** ($index - 1) + self::side5k * self::side5k * ($quadCurrent - 1),
                'quadCurrent' => $quadCurrent,
            ],
        ];

        if (!isset($configs[$index])) {
            throw new \InvalidArgumentException("Invalid index: $index");
        }

        $config = $configs[$index];

        [$side, $offset] = array_values($config);
        $quadCurrent = $config['quadCurrent'] ?? 1;
        $calcCRC = in_array('--crc', $argv, true) && in_array('--mix', $argv, true);

        return new ConfigDTO($index, $side, $offset, $quadCurrent, $calcCRC);
    }
}
