<?php

namespace App\Util;

class CompassCharacterUtil
{
    const NORTH = 'N';
    const EAST = 'E';
    const SOUTH = 'S';
    const WEST = 'W';

    const RIGHT = 'R';
    const LEFT = 'L';
    const MOVE = 'M';

    /**
     * @param string $rightOrLeft
     * @param string $compassCharacter
     *
     * @return string
     */
    public static function calculateCompassCharacter(string $rightOrLeft, string $compassCharacter): string
    {
        if ($rightOrLeft === self::RIGHT) {
            return match ($compassCharacter) {
                self::NORTH => self::EAST,
                self::EAST => self::SOUTH,
                self::SOUTH => self::WEST,
                self::WEST => self::NORTH,
            };
        }

        return match ($compassCharacter) {
            self::NORTH => self::WEST,
            self::WEST => self::SOUTH,
            self::SOUTH => self::EAST,
            self::EAST => self::NORTH,
        };
    }

    /**
     * @param int    $xCoordinate
     * @param int    $yCoordinate
     * @param string $compassCharacter
     *
     * @return array
     */
    public static function calculateXorYCoordination(int $xCoordinate, int $yCoordinate, string $compassCharacter): array
    {
        $yCoordinate = match ($compassCharacter) {
            self::NORTH => $yCoordinate + 1,
            self::SOUTH => $yCoordinate - 1,
            default => $yCoordinate,
        };

        $xCoordinate = match ($compassCharacter) {
            self::EAST => $xCoordinate + 1,
            self::WEST => $xCoordinate - 1,
            default => $xCoordinate,
        };

        return [$xCoordinate, $yCoordinate];
    }
}