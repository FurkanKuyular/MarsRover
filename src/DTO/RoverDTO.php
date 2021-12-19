<?php

namespace App\DTO;

class RoverDTO
{
    /**
     * @var string
     */
    protected string $commands;

    /**
     * @return string
     */
    public function getCommands(): string
    {
        return $this->commands;
    }

    /**
     * @param string $commands
     */
    public function setCommands(string $commands): void
    {
        $this->commands = $commands;
    }
}