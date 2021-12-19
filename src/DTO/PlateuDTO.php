<?php

namespace App\DTO;

class PlateuDTO
{
    /**
     * @var int
     */
    private int $xCoordination;

    /**
     * @var int
     */
    private int $yCoordination;

    /**
     * @var string
     */
    private string $compassCharacter;

    /**
     * @return int
     */
    public function getXCoordination(): int
    {
        return $this->xCoordination;
    }

    /**
     * @param int $xCoordination
     */
    public function setXCoordination(int $xCoordination): void
    {
        $this->xCoordination = $xCoordination;
    }

    /**
     * @return int
     */
    public function getYCoordination(): int
    {
        return $this->yCoordination;
    }

    /**
     * @param int $yCoordination
     */
    public function setYCoordination(int $yCoordination): void
    {
        $this->yCoordination = $yCoordination;
    }

    /**
     * @return string
     */
    public function getCompassCharacter(): string
    {
        return $this->compassCharacter;
    }

    /**
     * @param string $compassCharacter
     */
    public function setCompassCharacter(string $compassCharacter): void
    {
        $this->compassCharacter = $compassCharacter;
    }
}