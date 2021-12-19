<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;

class AbstractService
{
    /**
     * @param LoggerInterface    $logger
     * @param ContainerInterface $container
     */
    public function __construct(protected LoggerInterface $logger, protected ContainerInterface $container)
    {}
}