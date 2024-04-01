<?php

declare(strict_types=1);

namespace src\Utils\Calc;

class InputValidator
{
    public static function validateArguments(array $argv): bool
    {
        if (count($argv) < 4) {
            echo "Use: php calc.php <from> <to> <value> [--stat]\n";
            return false;
        }

        if (!is_numeric($argv[1]) || !is_numeric($argv[2])) {
            echo "Error: Arguments <from> and <to> must be numbers.\n";
            return false;
        }

        return true;
    }
}
