<?php
namespace youkchan\OpenassetsPHP\Protocol;
use Exception;


class OutputType
{
    const UNCOLORED = 0;
    const MARKER_OUTPUT = 1;
    const ISSUANCE = 2;
    const TRANSFER = 3;

    public static function isLabel($type)
    {
        if ($type == self::UNCOLORED) {
            return true;
        } elseif ($type == self::MARKER_OUTPUT) {
            return true;
        } elseif ($type == self::ISSUANCE) {
            return true;
        } elseif ($type == self::TRANSFER) {
            return true;
        } else {
            return false;
        }
    }
}
