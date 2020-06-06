<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Tests\Application;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use LRH\Bundle\DimensionBundle\LRHDimensionBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;

class Kernel extends SymfonyKernel
{
    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new LRHDimensionBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getProjectDir() . '/config/config.yaml');
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }
}
