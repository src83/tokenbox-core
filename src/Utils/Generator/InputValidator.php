<?php

declare(strict_types=1);

namespace src\Utils\Generator;

class InputValidator
{
    public static function validateArguments(array $argv): bool
    {
        if (count($argv) < 2) {
            echo "Use: php generator.php <index> [--mix] [--crc] [--pb] [--stat]\n";
            return false;
        }

        if (!is_numeric($argv[1])) {
            echo "Error: Argument <index> must be number.\n";
            return false;
        }

        if ($argv[1] > 4) {  // index == 5+

            if (count($argv) < 3) {
                echo "Use: php generator.php <index> <quadCurrent> [--stat]\n";
                return false;
            }

            if (!is_numeric($argv[1]) || !is_numeric($argv[2])) {
                echo "Error: Arguments <index> and <quadCurrent> must be numbers.\n";
                return false;
            }

        }

        return true;
    }
}
