<?php

declare(strict_types=1);

use LRH\Bundle\DimensionBundle\Tests\Application\Kernel;
use Symfony\Component\DependencyInjection\ContainerInterface;

require dirname(__DIR__) . '/Application/config/bootstrap.php';

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

/** @var ContainerInterface $container */
$container = $kernel->getContainer();

return $container->get('doctrine')->getManager();
